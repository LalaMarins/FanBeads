<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Inclui o autoloader do Composer (bibliotecas externas como PHPMailer)
require __DIR__ . '/vendor/autoload.php';
// --- Autoloader de Classes ---
// Esta função é chamada automaticamente pelo PHP sempre que uma classe
// que ainda não foi carregada é instanciada. Isso elimina a necessidade
// de usar `require_once` para cada classe.
spl_autoload_register(function ($className) {
    // Define os diretórios onde as classes podem ser encontradas.
    $directories = ['Controllers/', 'Models/'];

    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . $className . '.class.php';
        if (file_exists($file)) {
            require_once $file;
            return; // Encerra a busca assim que encontrar o arquivo.
        }
    }
});

// 1. Carrega as definições de rotas
require_once 'rotas.php';

// 2. Processa a requisição do usuário
$method = $_SERVER['REQUEST_METHOD']; // Ex: 'GET' ou 'POST'
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Pega apenas o caminho, sem "?query=string"

// 3. Normaliza a URI para o ambiente de desenvolvimento
// Remove o nome do subdiretório (ex: /fanbeads) da URI.
$baseDir = '/fanbeads';
if (str_starts_with($uri, $baseDir)) {
    $uri = substr($uri, strlen($baseDir));
}
// Garante que a raiz do site seja sempre "/" e não uma string vazia.
if (empty($uri)) {
    $uri = '/';
}

// 4. Delega para o roteador encontrar e executar a rota correspondente
$router->handleRequest($method, $uri);