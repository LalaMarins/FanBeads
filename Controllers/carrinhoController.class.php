<?php
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
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
    $preferenceId = null; // Inicia como nulo

    // 1. Calcula totais e índices (como antes)
    foreach ($itensCarrinho as $index => &$item) {
        $item['subtotal'] = $item['quantidade'] * $item['preco'];
        $item['index'] = $index;
        $total += $item['subtotal'];
    }
    unset($item);

    // 2. Se o carrinho NÃO estiver vazio, cria a preferência de pagamento
    if (!empty($itensCarrinho)) {
        try {
            // --- CONFIGURA O MERCADO PAGO (SINTAXE V3) ---
            $seuAccessToken = "APP_USR-8360318833146032-102219-9a1f1cf0e549879f8beab07e92267306-2941917038"; 
            MercadoPagoConfig::setAccessToken($seuAccessToken);

            // --- MONTAGEM DOS ITENS PARA A API ---
            $itensParaAPI = [];
            foreach ($itensCarrinho as $itemArray) {
                $itensParaAPI[] = [
                    'title' => $itemArray['nome'] . " (" . $itemArray['cor'] . " / " . $itemArray['tamanho'] . ")",
                    'quantity' => (int)$itemArray['quantidade'],
                    'unit_price' => (float)$itemArray['preco'],
                    'currency_id' => "BRL"
                ];
            }

            // --- CRIA A PREFERÊNCIA DE PAGAMENTO (SINTAXE V3) ---
            $client = new PreferenceClient();
            $preference = $client->create([
                "items" => $itensParaAPI,
                "back_urls" => [
                    "success" => "http://localhost/fanbeads/pedido/pagamento-sucesso",
                    "failure" => "http://localhost/fanbeads/carrinho?pagamento=falha",
                    "pending" => "http://localhost/fanbeads/carrinho?pagamento=pendente"
                ],
                // A linha "auto_return" foi removida, como fizemos antes
            ]);

            // 3. SALVA O ID DA PREFERÊNCIA PARA A VIEW
            $preferenceId = $preference->id;

        } catch (Exception $e) {
            // Se falhar (ex: token errado), apenas não mostrará o botão
            error_log('Erro ao criar preferência MP no carrinho: ' . $e->getMessage());
        }
    }

    // 4. Carrega a view (passando as variáveis de antes + $preferenceId)
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