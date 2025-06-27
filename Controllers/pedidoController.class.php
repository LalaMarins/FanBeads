<?php

class PedidoController {
    public function feito() {
        session_start();
    
        $mensagem = "";
    
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $mensagem .= "Aqui está o resumo do seu pedido:<br><ul>";
    
            foreach ($_SESSION['cart'] as $item) {
                $quantidade = intval($item['quantidade'] ?? 0);
                $cor = htmlspecialchars($item['cor'] ?? '');
                $tamanho = htmlspecialchars($item['tamanho'] ?? '');
                $nome = htmlspecialchars($item['nome'] ?? 'Produto');
                $categoria = "";
    
                // Detectar se é um Phone Strap (heurística pelo nome ou outra lógica simples)
                if (stripos($nome, 'Phone Straps') !== false) {
                    $categoria = 'Phone Straps';
                }
    
                $mensagem .= "<li>{$quantidade}x {$nome} - Cor: {$cor}";
    
                if ($categoria !== 'Phone Straps') {
                    $mensagem .= ", Tamanho: {$tamanho}";
                }
    
                $mensagem .= "</li>";
            }
    
            $mensagem .= "</ul>";
            unset($_SESSION['cart']);
        } else {
            $mensagem = "Seu carrinho estava vazio.";
        }
    
        include 'Views/pedido.php';
    }
    
}
