<?php
class PedidoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexao::getInstancia();
    }

    /**
     * Cria um novo pedido no banco de dados.
     * Esta função usa uma transação para garantir que o pedido e seus itens
     * sejam salvos juntos. Se algo der errado, nada é salvo.
     */
   public function criar(Pedido $pedido): int|false
{
    try {
        // Inicia a transação
        $this->db->beginTransaction();

        // 1. Insere o pedido principal na tabela 'pedidos'
        $sqlPedido = "INSERT INTO pedidos (id_usuario, valor_total) VALUES (:id_usuario, :valor_total)";
        $stmtPedido = $this->db->prepare($sqlPedido);
        $stmtPedido->bindValue(':id_usuario', $pedido->getIdUsuario());
        $stmtPedido->bindValue(':valor_total', $pedido->getValorTotal());
        $stmtPedido->execute();

        // Pega o ID do pedido que acabou de ser criado
        $idPedido = (int)$this->db->lastInsertId();

        // 2. Insere cada item do pedido na tabela 'pedido_itens'
        $sqlItem = "INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario, cor, tamanho) 
                    VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario, :cor, :tamanho)";
        $stmtItem = $this->db->prepare($sqlItem);

        foreach ($pedido->getItens() as $item) {
            $stmtItem->bindValue(':id_pedido', $idPedido);
            $stmtItem->bindValue(':id_produto', $item->getIdProduto());
            $stmtItem->bindValue(':quantidade', $item->getQuantidade());
            $stmtItem->bindValue(':preco_unitario', $item->getPrecoUnitario());
            $stmtItem->bindValue(':cor', $item->getCor());
            $stmtItem->bindValue(':tamanho', $item->getTamanho());
            $stmtItem->execute();
        }

        // Se tudo deu certo, confirma a transação
        $this->db->commit();
        return $idPedido; // <-- MUDANÇA IMPORTANTE: Retorna o ID do pedido criado

    } catch (PDOException $e) {
        // Se algo deu errado, desfaz a transação
        $this->db->rollBack();
        error_log('Erro ao criar pedido: ' . $e->getMessage()); // É uma boa prática registrar o erro
        return false;
    }
    }

/**
 * Busca todos os pedidos de um usuário específico.
 * @return Pedido[] Retorna uma lista de objetos Pedido.
 */
public function buscarPorUsuario(int $idUsuario): array
{
    // 1. Busca todos os pedidos principais do usuário
    $sqlPedidos = "SELECT * FROM pedidos WHERE id_usuario = :id_usuario ORDER BY data_pedido DESC";
    $stmtPedidos = $this->db->prepare($sqlPedidos);
    $stmtPedidos->bindValue(':id_usuario', $idUsuario);
    $stmtPedidos->execute();

    $pedidos = [];
    while ($rowPedido = $stmtPedidos->fetch(PDO::FETCH_ASSOC)) {
        $pedido = new Pedido();
        $pedido->setId($rowPedido['id_pedido']);
        $pedido->setIdUsuario($rowPedido['id_usuario']);
        $pedido->setDataPedido($rowPedido['data_pedido']);
        $pedido->setValorTotal((float)$rowPedido['valor_total']);
        $pedido->setStatus($rowPedido['status']);
        $pedidos[] = $pedido;
    }

    // Se não encontrou pedidos, retorna a lista vazia
    if (empty($pedidos)) {
        return [];
    }

    // 2. Para cada pedido encontrado, busca seus itens
    $sqlItens = "SELECT pi.*, p.nome AS nome_produto, p.imagem AS imagem_produto
                 FROM pedido_itens pi
                 JOIN produto p ON pi.id_produto = p.id_produto
                 WHERE pi.id_pedido = :id_pedido";
    $stmtItens = $this->db->prepare($sqlItens);

    foreach ($pedidos as $pedido) {
        $stmtItens->bindValue(':id_pedido', $pedido->getId());
        $stmtItens->execute();
        
        $itens = [];
        while ($rowItem = $stmtItens->fetch(PDO::FETCH_ASSOC)) {
            // Aqui estamos adicionando os dados do item como um array associativo
            // para simplificar a exibição na view.
            $itens[] = $rowItem;
        }
        $pedido->setItens($itens);
    }

    return $pedidos;
}
}