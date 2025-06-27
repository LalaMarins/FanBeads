<?php
// index.php

// 1) Carrega o roteador e já define as rotas
require_once 'rotas.php';

// 2) Captura método e URI brutos
$method = $_SERVER['REQUEST_METHOD'];
$uriRaw = $_SERVER['REQUEST_URI'];

// 3) Remove query string (?id=...) — .e.g "/fanbeads/detalhes?id=1" vira "/fanbeads/detalhes"
$uriPath = parse_url($uriRaw, PHP_URL_PATH);

// 4) Remove o prefixo "/fanbeads" da URI, se houver
$prefix = '/fanbeads';
if (str_starts_with($uriPath, $prefix)) {
    $uriPath = substr($uriPath, strlen($prefix));
    if ($uriPath === '') {
        $uriPath = '/';
    }
}

// 5) Passa para o roteador
$rotas->verificar_rota($method, $uriPath);
