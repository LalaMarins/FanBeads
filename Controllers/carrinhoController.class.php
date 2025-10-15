<?php
class CarrinhoController
{
    private ProdutoDAO $produtoDAO;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        // Garante que o carrinho sempre exista na sessão, mesmo que vazio.
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
        
        // Instancia o DAO no construtor para reutilizá-lo nos métodos.
        $this->produtoDAO = new ProdutoDAO();
    }

    /**
     * Exibe a página do carrinho com os itens, subtotais e total.
     */
    public function index(): void
    {
        $itensCarrinho = $_SESSION['cart'];
        $total = 0;

        // Calcula o subtotal para cada item e o total do pedido.
        // Adiciona um 'index' para facilitar a remoção/atualização via AJAX.
        foreach ($itensCarrinho as $index => &$item) {
            $item['subtotal'] = $item['quantidade'] * $item['preco'];
            $item['index'] = $index;
            $total += $item['subtotal'];
        }
        unset($item); // Boa prática: remove a referência do loop.

        // Passa as variáveis para a view.
        require 'Views/carrinho.php';
    }

    /**
     * Adiciona um produto ao carrinho ou incrementa sua quantidade se já existir.
     */
    public function adicionar(): void
    {
        $produtoId = (int)($_POST['produto_id'] ?? 0);
        $cor = trim($_POST['cor'] ?? 'N/A');
        
        // Lógica para lidar com tamanho padrão ou personalizado.
        $tamanho = trim($_POST['tamanho'] ?? 'Padrão');
        if ($tamanho === 'extra') {
            $tamanhoExtra = trim($_POST['tamanho_extra'] ?? '');
            $tamanho = !empty($tamanhoExtra) ? $tamanhoExtra : 'Personalizado';
        }

        // Validação: se não houver ID do produto ou cor, não faz nada.
        if ($produtoId <= 0 || empty($cor)) {
            header('Location: /fanbeads/carrinho');
            exit;
        }

        // Busca o produto no banco para garantir que ele existe e pegar dados atualizados.
        $produto = $this->produtoDAO->buscarPorId($produtoId);
        if (!$produto) {
            // Se o produto não existe, não adiciona ao carrinho.
            header('Location: /fanbeads/');
            exit;
        }

        // Verifica se um item idêntico (mesmo produto, cor e tamanho) já está no carrinho.
        $indexItemExistente = null;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['produto_id'] === $produtoId && $item['cor'] === $cor && $item['tamanho'] === $tamanho) {
                $indexItemExistente = $index;
                break;
            }
        }

        if ($indexItemExistente !== null) {
            // Se o item já existe, apenas incrementa a quantidade.
            $_SESSION['cart'][$indexItemExistente]['quantidade']++;
        } else {
            // Se é um item novo, adiciona ao carrinho.
            $_SESSION['cart'][] = [
                'produto_id' => $produto->getId(),
                'nome'       => $produto->getNome(),
                'preco'      => $produto->getPreco(),
                'imagem'     => $produto->getImagem(),
                'quantidade' => 1,
                'cor'        => $cor,
                'tamanho'    => $tamanho,
            ];
        }

        header('Location: /fanbeads/carrinho');
        exit;
    }

    /**
     * Atualiza a quantidade de um item no carrinho (usado via AJAX).
     */
    public function atualizar(): void
    {
        $index = key($_POST['quantidade']); // Pega o índice do item
        $quantidade = (int)current($_POST['quantidade']); // Pega a nova quantidade

        if (isset($_SESSION['cart'][$index])) {
            if ($quantidade > 0) {
                $_SESSION['cart'][$index]['quantidade'] = $quantidade;
            } else {
                // Se a quantidade for 0 ou menor, remove o item.
                unset($_SESSION['cart'][$index]);
            }
        }

        // Reindexa o array para evitar "buracos" nas chaves.
        $_SESSION['cart'] = array_values($_SESSION['cart']);

        // Responde ao AJAX com "204 No Content", indicando sucesso sem conteúdo de retorno.
        http_response_code(204);
        exit;
    }

    /**
     * Remove um item do carrinho (usado via AJAX).
     */
    public function remover(): void
    {
        $index = (int)($_POST['index'] ?? -1);

        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
        }

        $_SESSION['cart'] = array_values($_SESSION['cart']);

        http_response_code(204);
        exit;
    }
}