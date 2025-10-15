<?php
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function handleRequest(string $method, string $uri): void
    {
        if (isset($this->routes[$method][$uri])) {
            [$class, $func] = $this->routes[$method][$uri];
            (new $class)->$func();
        } else {
            http_response_code(404);
            // Para um projeto final, é ideal ter uma página de erro bonita.
            // require 'Views/404.php';
            echo "Erro 404: Página não encontrada.";
        }
    }
}

// === Instancia o roteador ===
$router = new Router();

// === Definição das Rotas ===

// --- Páginas Gerais ---
$router->get('/', [InicioController::class, 'index']);
$router->get('/detalhes', [ProdutoController::class, 'detalhes']);

// --- Produtos (Padrão CRUD) ---
$router->get('/produtos', [ProdutoController::class, 'listar']);
$router->get('/pulseiras', [ProdutoController::class, 'listarPulseiras']);
$router->get('/chaveiros', [ProdutoController::class, 'listarChaveiros']);

// --- Produtos (Admin) ---
$router->get('/produtos/novo', [ProdutoController::class, 'novo']);      
$router->post('/produtos/criar', [ProdutoController::class, 'criar']);   
$router->post('/produtos/excluir', [ProdutoController::class, 'excluir']); 

// --- Autenticação ---
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// --- Carrinho ---
$router->get('/carrinho', [CarrinhoController::class, 'index']);
$router->post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar']);
$router->post('/carrinho/atualizar', [CarrinhoController::class, 'atualizar']);
$router->post('/carrinho/remover', [CarrinhoController::class, 'remover']);

// --- Pedidos ---
$router->get('/meus-pedidos', [PedidoController::class, 'historico']);
$router->post('/pedido/finalizar', [PedidoController::class, 'finalizar']); 
$router->get('/pedido/sucesso', [PedidoController::class, 'sucesso']);    