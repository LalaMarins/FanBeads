<?php
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

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
   /**
     * Processa os dados do carrinho (POST) para criar uma preferência
     * de pagamento no Mercado Pago (usando SDK v3) e redirecionar o usuário.
     */
    public function finalizar(): void
    {
        // --- VALIDAÇÕES (continua igual) ---
        if (!isset($_SESSION['user'])) {
            header('Location: /fanbeads/login');
            exit;
        }
        if (empty($_SESSION['cart'])) {
            header('Location: /fanbeads/carrinho');
            exit;
        }

        // --- CONFIGURA O MERCADO PAGO (SINTAXE V3) ---
        // 1. Cole seu Access Token (o da imagem) aqui
        $seuAccessToken = "APP_USR-8360318833146032-102219-9a1f1cf0e549879f8beab07e92267306-2941917038"; 
        
        try {
            // Inicializa o SDK V3
            MercadoPagoConfig::setAccessToken($seuAccessToken);
        } catch (Exception $e) {
            // Erro se o SDK falhar ao ser configurado
            error_log('Erro ao configurar Access Token MP: ' . $e->getMessage());
            header('Location: /fanbeads/carrinho?error=falha_gateway_config');
            exit;
        }

        // --- MONTAGEM DOS ITENS PARA A API ---
        $carrinho = $_SESSION['cart'];
        $itensParaAPI = [];
        $totalPedido = 0;

        foreach ($carrinho as $itemArray) {
            // Na V3, os itens são um array associativo
            $itensParaAPI[] = [
                'title' => $itemArray['nome'] . " (" . $itemArray['cor'] . " / " . $itemArray['tamanho'] . ")",
                'quantity' => (int)$itemArray['quantidade'],
                'unit_price' => (float)$itemArray['preco'],
                'currency_id' => "BRL"
            ];
            $totalPedido += $itemArray['preco'] * $itemArray['quantidade'];
        }

        // --- CRIA A PREFERÊNCIA DE PAGAMENTO (SINTAXE V3) ---
        try {
            // 2. Cria o "Client" de Preferência
            $client = new PreferenceClient();
            
            // 3. Cria a preferência passando um array de configuração
            $preference = $client->create([
                "items" => $itensParaAPI,
                "back_urls" => [
                    "success" => "http://localhost/fanbeads/pedido/pagamento-sucesso",
                    "failure" => "http://localhost/fanbeads/carrinho?pagamento=falha",
                    "pending" => "http://localhost/fanbeads/carrinho?pagamento=pendente"
                ],

            ]);

            // --- REDIRECIONA O USUÁRIO ---
            // 4. Redireciona o usuário para a URL de pagamento
            // (O $preference->init_point existe no objeto de resposta)
            header('Location: ' . $preference->init_point);
            exit;

        } catch (MPApiException $e) {
            // Erro específico da API do MP (ex: token errado, dados inválidos)
            // Agora vamos ver o erro em vez da tela branca!
            echo "Erro da API do Mercado Pago: " . $e->getMessage() . "<br>";
            echo "<pre>";
            print_r($e->getApiResponse());
            echo "</pre>";
            error_log('Erro da API Mercado Pago: ' . $e->getMessage());
            // header('Location: /fanbeads/carrinho?error=falha_gateway_api');
            exit;
        } catch (Exception $e) {
            // Outro erro (ex: falha de rede)
            echo "Erro geral ao criar preferência: " . $e->getMessage();
            error_log('Erro geral ao criar preferência MP: ' . $e->getMessage());
            // header('Location: /fanbeads/carrinho?error=falha_gateway_geral');
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