<?php
require_once 'Models/Conexao.class.php';
require_once 'Models/ProdutoDAO.class.php';
require_once 'Models/Produto.class.php';

class carrinhoController
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
    }

    public function adicionar(): void
    {
        $id     = intval($_POST['produto_id'] ?? 0);
        $tam    = $_POST['tamanho'] ?? '';
        $extra  = trim($_POST['tamanho_extra'] ?? '');
        if ($tam === 'extra' && $extra !== '') {
            $tam = $extra;
        }
        $cor = $_POST['cor'] ?? '';
        $qty = 1;

        $p = (new ProdutoDAO( Conexao::getInstancia() ))->buscarPorId($id);
        $item = [
            'produto_id' => $id,
            'nome'       => $p->getNome(),
            'preco'      => $p->getPreco(),
            'imagem'     => $p->getImagem(),
            'tamanho'    => $tam,
            'cor'        => $cor,
            'quantidade' => $qty
        ];

        $achou = false;
        foreach ($_SESSION['cart'] as &$c) {
            if (
                $c['produto_id'] === $item['produto_id'] &&
                $c['tamanho']    === $item['tamanho']    &&
                $c['cor']        === $item['cor']
            ) {
                $c['quantidade']++;
                $achou = true;
                break;
            }
        }
        unset($c);

        if (!$achou) {
            $_SESSION['cart'][] = $item;
        }

        // redireciona normalmente após adicionar
        header('Location: carrinho');
        exit;
    }

    public function index(): void
    {
        $total = 0;
        $items = [];
        foreach ($_SESSION['cart'] as $idx => $ci) {
            $sub = $ci['quantidade'] * $ci['preco'];
            $total += $sub;
            $ci['subtotal'] = $sub;
            $ci['index']    = $idx;
            $items[] = $ci;
        }
        require 'Views/carrinho.php';
    }

    public function atualizar(): void
    {
        foreach ($_POST['quantidade'] as $idx => $q) {
            $q = intval($q);
            if (isset($_SESSION['cart'][$idx])) {
                if ($q > 0) {
                    $_SESSION['cart'][$idx]['quantidade'] = $q;
                } else {
                    unset($_SESSION['cart'][$idx]);
                }
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        // responde 204 No Content para o AJAX
        http_response_code(204);
        exit;
    }

    public function remover(): void
    {
        $idx = intval($_POST['index'] ?? -1);
        if (isset($_SESSION['cart'][$idx])) {
            unset($_SESSION['cart'][$idx]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }

        // responde 204 No Content para o AJAX
        http_response_code(204);
        exit;
    }
}
