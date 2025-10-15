<?php
// Controllers/pedidoController.class.php

/**
 * Gerencia a finalização de pedidos e o histórico de compras do usuário.
 */
class PedidoController
{
    private PedidoDAO $pedidoDAO;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->pedidoDAO = new PedidoDAO();
    }

    /**
     * Processa os dados do carrinho (POST) para criar e salvar um novo pedido.
     * Após salvar, redireciona para a página de sucesso para evitar reenvio do formulário.
     */
    public function finalizar(): void
    {
        // --- VALIDAÇÕES ---
        if (!isset($_SESSION['user'])) {
            header('Location: /fanbeads/login');
            exit;
        }
        if (empty($_SESSION['cart'])) {
            header('Location: /fanbeads/carrinho');
            exit;
        }

        // --- MONTAGEM DO PEDIDO ---
        $carrinho = $_SESSION['cart'];
        $itensParaSalvar = [];
        $totalPedido = 0;

        foreach ($carrinho as $itemArray) {
            $itemObj = new PedidoItem();
            $itemObj->setIdProduto($itemArray['produto_id']);
            $itemObj->setQuantidade($itemArray['quantidade']);
            $itemObj->setPrecoUnitario($itemArray['preco']);
            $itemObj->setCor($itemArray['cor']);
            $itemObj->setTamanho($itemArray['tamanho']);
            
            $itensParaSalvar[] = $itemObj;
            $totalPedido += $itemArray['preco'] * $itemArray['quantidade'];
        }

        $pedido = new Pedido();
        $pedido->setIdUsuario($_SESSION['user']['id']);
        $pedido->setValorTotal($totalPedido);
        $pedido->setItens($itensParaSalvar);

        // --- SALVANDO NO BANCO ---
        $novoPedidoId = $this->pedidoDAO->criar($pedido);

        if ($novoPedidoId) {
            // Se o pedido foi salvo, guarda os detalhes na sessão para a página de sucesso.
            $_SESSION['pedido_sucesso'] = [
                'numero_pedido' => $novoPedidoId,
                'itens_pedido'  => $carrinho,
                'total_pedido'  => $totalPedido
            ];
            unset($_SESSION['cart']); // Limpa o carrinho
            
            // Redireciona para a página de sucesso (Padrão Post-Redirect-Get).
            header('Location: /fanbeads/pedido/sucesso');
            exit;
        }

        // Se deu erro ao salvar, redireciona de volta para o carrinho com uma mensagem.
        header('Location: /fanbeads/carrinho?error=falha_pedido');
        exit;
    }

    /**
     * Exibe a página de confirmação de pedido bem-sucedido (GET).
     */
    public function sucesso(): void
    {
        // Verifica se há dados de um pedido recém-finalizado na sessão.
        if (!isset($_SESSION['pedido_sucesso'])) {
            // Se não houver, redireciona para a página inicial para evitar acesso direto.
            header('Location: /fanbeads/');
            exit;
        }

        // Passa os dados do pedido para variáveis locais.
        $numero_pedido = $_SESSION['pedido_sucesso']['numero_pedido'];
        $itens_pedido = $_SESSION['pedido_sucesso']['itens_pedido'];
        $total_pedido = $_SESSION['pedido_sucesso']['total_pedido'];

        // Limpa os dados da sessão para que a página não seja exibida novamente ao recarregar.
        unset($_SESSION['pedido_sucesso']);

        // Renderiza a view de sucesso.
        require 'Views/pedido.php';
    }

    /**
     * Exibe o histórico de pedidos do usuário logado.
     */
    public function historico(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /fanbeads/login');
            exit;
        }

        $idUsuario = $_SESSION['user']['id'];
        $pedidos = $this->pedidoDAO->buscarPorUsuario($idUsuario);

        require 'Views/meus_pedidos.php';
    }
}