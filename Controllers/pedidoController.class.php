<?php
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
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

    // --- CONFIGURA O MERCADO PAGO ---
    // 1. Cole seu Access Token de TESTE aqui
    $seuAccessToken = "APP_USR-8360318833146032-102219-9a1f1cf0e549879f8beab07e92267306-2941917038"; 
    SDK::setAccessToken($seuAccessToken);

    // --- MONTAGEM DOS ITENS PARA A API ---
    $carrinho = $_SESSION['cart'];
    $itensParaAPI = [];
    $totalPedido = 0;

    foreach ($carrinho as $itemArray) {
        $item = new Item();
        $item->title = $itemArray['nome'] . " (" . $itemArray['cor'] . " / " . $itemArray['tamanho'] . ")";
        $item->quantity = $itemArray['quantidade'];
        $item->unit_price = (float)$itemArray['preco']; // Preço deve ser float
        $item->currency_id = "BRL"; // Moeda (Real Brasileiro)

        $itensParaAPI[] = $item;
        $totalPedido += $itemArray['preco'] * $itemArray['quantidade'];
    }

    // --- CRIA A PREFERÊNCIA DE PAGAMENTO ---
    try {
        $preference = new Preference();
        $preference->items = $itensParaAPI;

        // 3. Define as URLs de retorno (para onde o MP te envia após o pagamento)
        // (Vamos criar essas rotas no próximo passo)
        $preference->back_urls = [
            "success" => "http://localhost/fanbeads/pedido/pagamento-sucesso",
            "failure" => "http://localhost/fanbeads/carrinho?pagamento=falha",
            "pending" => "http://localhost/fanbeads/carrinho?pagamento=pendente"
        ];
        $preference->auto_return = "approved"; // Retorna automaticamente se aprovado

        // Salva a preferência (envia para a API do MP)
        $preference->save();

        // --- REDIRECIONA O USUÁRIO ---
        // 4. Redireciona o usuário para a URL de pagamento (init_point)
        // (Em modo Sandbox, ele vai para uma página de pagamento de teste)
        header('Location: ' . $preference->init_point);
        exit;

    } catch (Exception $e) {
        // Se der erro ao criar a preferência, volta ao carrinho
        error_log('Erro ao criar preferência do Mercado Pago: ' . $e->getMessage());
        header('Location: /fanbeads/carrinho?error=falha_gateway');
        exit;
    }
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
    /**
     * (GET) Esta é a rota que o Mercado Pago chama após um pagamento APROVADO.
     * É AQUI que o pedido é salvo no banco de dados, o carrinho é limpo
     * e o usuário é redirecionado para a página final de "Obrigado".
     */
    public function pagamentoSucesso(): void
    {
        // --- VALIDAÇÕES ---
        // Garante que o usuário está logado e que o carrinho não está vazio
        // (Evita que o usuário acesse esta URL diretamente)
        if (!isset($_SESSION['user'])) {
            header('Location: /fanbeads/login');
            exit;
        }
        if (empty($_SESSION['cart'])) {
            header('Location: /fanbeads/carrinho');
            exit;
        }

        // --- LÓGICA PARA SALVAR O PEDIDO (Movida do 'finalizar') ---
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
            // Se o pedido foi salvo, guarda os detalhes para a página de sucesso.
            $_SESSION['pedido_sucesso'] = [
                'numero_pedido' => $novoPedidoId,
                'itens_pedido'  => $carrinho,
                'total_pedido'  => $totalPedido
            ];
            
            // LIMPA O CARRINHO AQUI!
            unset($_SESSION['cart']); 
            
            // Redireciona para a página de sucesso (a view final)
            header('Location: /fanbeads/pedido/sucesso');
            exit;
        }

        // Se deu erro ao salvar, redireciona de volta para o carrinho.
        header('Location: /fanbeads/carrinho?error=falha_pedido');
        exit;
    }
}