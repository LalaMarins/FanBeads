<?php
class PedidoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexao::getInstancia();
    }

    /**
     * Cria um novo pedido e seus itens no banco de dados usando uma transação.
     * @return int|false Retorna o ID do novo pedido em caso de sucesso, ou false em caso de erro.
     */
    public function criar(Pedido $pedido): int|false
    {
        try {
            $this->db->beginTransaction();

            $sqlPedido = "INSERT INTO pedidos (id_usuario, valor_total) VALUES (:id_usuario, :valor_total)";
            $stmtPedido = $this->db->prepare($sqlPedido);
            $stmtPedido->bindValue(':id_usuario', $pedido->getIdUsuario());
            $stmtPedido->bindValue(':valor_total', $pedido->getValorTotal());
            $stmtPedido->execute();

            $idPedido = (int)$this->db->lastInsertId();

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

            $this->db->commit();
            return $idPedido;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Erro ao criar pedido: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os pedidos de um usuário específico.
     * @return Pedido[] Retorna uma lista de objetos Pedido, cada um com seus itens.
     */
    public function buscarPorUsuario(int $idUsuario): array
    {
        // 1. Busca todos os pedidos principais do usuário.
        $sqlPedidos = "SELECT * FROM pedidos WHERE id_usuario = :id_usuario ORDER BY data_pedido DESC";
        $stmtPedidos = $this->db->prepare($sqlPedidos);
        $stmtPedidos->bindValue(':id_usuario', $idUsuario);
        $stmtPedidos->execute();

        $pedidos = $stmtPedidos->fetchAll(PDO::FETCH_CLASS, Pedido::class);

        // Se não encontrou pedidos, retorna a lista vazia.
        if (empty($pedidos)) {
            return [];
        }

        // 2. Para cada pedido, busca seus respectivos itens.
        // Nota: Esta abordagem (N+1 query) é simples e funcional. Para um TCC, é aceitável.
        // Em um sistema de alta performance, seria ideal otimizar isso para uma única consulta complexa.
        $sqlItens = "SELECT pi.*, p.nome AS nome_produto, p.imagem AS imagem_produto
                     FROM pedido_itens pi
                     JOIN produto p ON pi.id_produto = p.id_produto
                     WHERE pi.id_pedido = :id_pedido";
        $stmtItens = $this->db->prepare($sqlItens);

        foreach ($pedidos as $pedido) {
            $stmtItens->bindValue(':id_pedido', $pedido->getId());
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
            $pedido->setItens($itens);
        }

        return $pedidos;
    }
}