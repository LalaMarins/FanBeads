<?php
// Garante que a sessão esteja iniciada para o menu.php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= htmlspecialchars($pageTitle ?? 'FanBeads') ?></title>
    
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="/fanbeads/assets/img/happy-face.png">
    <script src="/fanbeads/assets/js/script.js" defer></script>
    
    <script src="https://sdk.mercadopago.com/js/v2"></script>

</head>
<body>
    <?php require 'Views/menu.php'; ?>