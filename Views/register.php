<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FanBeads – Cadastro</title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="form-page">
        <h2>Cadastre-se</h2>
        <form action="/fanbeads/register" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="senha" required>

            <button type="submit">Criar Conta</button>
        </form>

        <div class="link-alternativo">
            Já tem conta? <a href="/fanbeads/login">Faça login</a>
        </div>
    </main>
</body>
</html>