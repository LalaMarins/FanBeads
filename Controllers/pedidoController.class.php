<?php
require_once 'Models/Pedidos.class.php';
require_once 'Models/PedidoItem.class.php';
require_once 'Models/PedidoDAO.class.php';

class PedidoController {
    
    public function feito() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    
        // --- VALIDAÇÕES ESSENCIAIS ---
        // 1. O usuário precisa estar logado para finalizar um pedido.
        if (!isset($_SESSION['user'])) {
            header('Location: /fanbeads/login'); // Se não estiver, manda para o login.
            exit;
        }

        // 2. O carrinho não pode estar vazio.
        if (empty($_SESSION['cart'])) {
            header('Location: /fanbeads/'); // Se estiver, manda para a loja.
            exit;
        }

        // --- MONTAGEM DO PEDIDO ---
        $carrinho = $_SESSION['cart'];
        $itens_pedido_obj = [];
        $total_pedido = 0;

        // Transforma os itens do carrinho (que são arrays) em objetos PedidoItem
        foreach ($carrinho as $itemArray) {
            $itemObj = new PedidoItem();
            $itemObj->setIdProduto($itemArray['produto_id']);
            $itemObj->setQuantidade($itemArray['quantidade']);
            $itemObj->setPrecoUnitario($itemArray['preco']);
            $itemObj->setCor($itemArray['cor']);
            $itemObj->setTamanho($itemArray['tamanho']);
            
            $itens_pedido_obj[] = $itemObj; // Adiciona o objeto à lista
            $total_pedido += $itemArray['preco'] * $itemArray['quantidade'];
        }

        // Cria o objeto Pedido principal
        $pedido = new Pedido();
        $pedido->setIdUsuario($_SESSION['user']['id']);
        $pedido->setValorTotal($total_pedido);
        $pedido->setItens($itens_pedido_obj);

        // --- SALVANDO NO BANCO DE DADOS ---
        $pedidoDAO = new PedidoDAO();
        $novoPedidoId = $pedidoDAO->criar($pedido);

        // --- PREPARANDO A VIEW DE SUCESSO ---
        if ($novoPedidoId) {
            // Se o pedido foi salvo com sucesso, prepara os dados para a view de confirmação
            $itens_pedido = $carrinho; // Reutiliza os dados do carrinho para a view
            $numero_pedido = $novoPedidoId; // Usa o ID real do banco!

            unset($_SESSION['cart']); // Limpa o carrinho
    
            include 'Views/pedido.php'; // Mostra a página de sucesso
        } else {
            // Se deu erro ao salvar, pode redirecionar para uma página de erro ou de volta ao carrinho
            // Por simplicidade, vamos apenas mostrar uma mensagem.
            echo "<h1>Erro ao processar seu pedido.</h1><p>Por favor, tente novamente.</p>";
            exit;
        }
    }
    public function historico()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Se o usuário não estiver logado, redireciona para o login
    if (!isset($_SESSION['user'])) {
        header('Location: /fanbeads/login');
        exit;
    }

    $idUsuario = $_SESSION['user']['id'];

    $pedidoDAO = new PedidoDAO();
    $pedidos = $pedidoDAO->buscarPorUsuario($idUsuario);

    // Carrega a view e passa a lista de pedidos para ela
    require 'Views/meus_pedidos.php';
}
}