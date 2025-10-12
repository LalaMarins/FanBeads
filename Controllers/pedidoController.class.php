<?php

class PedidoController {
    
    public function feito() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    
        // Se o carrinho estiver vazio ou não existir, redireciona para a loja.
        if (empty($_SESSION['cart'])) {
            header('Location: /fanbeads/');
            exit;
        }

        // Prepara os dados para a view
        $itens_pedido = $_SESSION['cart']; // Envia a lista completa de itens
        $total_pedido = 0;
        foreach ($itens_pedido as $item) {
            $total_pedido += $item['preco'] * $item['quantidade'];
        }

        // Gera um número de pedido "fake" para mostrar ao usuário
        $numero_pedido = strtoupper(substr(uniqid(), -6));

        // IMPORTANTE: Limpa o carrinho da sessão APÓS preparar os dados.
        unset($_SESSION['cart']);
    
        // Inclui a view, que agora terá acesso às variáveis $itens_pedido, $total_pedido e $numero_pedido
        include 'Views/pedido.php';
    }
}