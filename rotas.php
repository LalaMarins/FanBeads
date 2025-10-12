<?php


class rotas
{
    private array $rotas = [];

    public function get(string $path, array $call): void
    {
        $this->rotas['GET'][$path] = $call;
    }

    public function post(string $path, array $call): void
    {
        $this->rotas['POST'][$path] = $call;
    }

    public function verificar_rota(string $method, string $uri): void
    {
        if (isset($this->rotas[$method][$uri])) {
            [$class, $func] = $this->rotas[$method][$uri];
            (new $class)->$func();
        } else {
            http_response_code(404);
            echo "Rota inválida: {$uri}";
        }
    }
}

// carrega controllers
require_once 'Controllers/inicioController.class.php';
require_once 'Controllers/produtoController.class.php';
require_once 'Controllers/carrinhoController.class.php';
require_once 'Controllers/AuthController.class.php';
require_once 'Controllers/pedidoController.class.php';


// instancia roteador
$rotas = new rotas();

// === Página Inicial ===
$rotas->get('/', [inicioController::class, 'index']);

// === Produtos ===
$rotas->get('/produtos',              [produtoController::class, 'listar']);
$rotas->get('/produtos/pulseiras',    [produtoController::class, 'listarPulseiras']);
$rotas->get('/produtos/chaveiros',  [produtoController::class, 'listarChaveiros']);
// aliases sem /produtos prefixo
$rotas->get('/pulseiras',             [produtoController::class, 'listarPulseiras']);
$rotas->get('/chaveiros',           [produtoController::class, 'listarChaveiros']);

$rotas->get('/detalhes',              [produtoController::class, 'detalhes']);
$rotas->get('/produtos/novo', [produtoController::class,  'novo']);
$rotas->post('/produtos/novo',[produtoController::class,  'criar']);
$rotas->post('/produto/excluir', [produtoController::class, 'excluir']);



// === Autenticação ===
$rotas->get('/login',                 [AuthController::class, 'loginForm']);
$rotas->post('/login',                [AuthController::class, 'login']);
$rotas->get('/cadastrar',             [AuthController::class, 'cadastrarForm']);
$rotas->post('/cadastrar',            [AuthController::class, 'cadastrar']);
$rotas->get('/logout',                [AuthController::class, 'logout']);

// === Carrinho ===
$rotas->post('/adicionar_carrinho',   [carrinhoController::class, 'adicionar']);
$rotas->get('/carrinho',              [carrinhoController::class, 'index']);
$rotas->post('/carrinho/atualizar',   [carrinhoController::class, 'atualizar']);
$rotas->post('/carrinho/remover',     [carrinhoController::class, 'remover']);

$rotas->get('/pedido/feito', [PedidoController::class, 'feito']);
$rotas->post('/pedido/feito', [PedidoController::class, 'feito']);
$rotas->get('/meus-pedidos', [PedidoController::class, 'historico']);
