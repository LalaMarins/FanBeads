<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FanBeads – Recuperar Senha</title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="form-page">
        <h2>Recuperar Senha</h2>
        <p>Digite seu e-mail abaixo. Se ele estiver cadastrado, enviaremos instruções para redefinir sua senha.</p>

        <?php // Adicionaremos mensagens de sucesso/erro aqui depois ?>

        <form action="/fanbeads/forgot-password" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit" class="btn btn-primary">Enviar Instruções</button>
        </form>

        <div class="link-alternativo">
            Lembrou a senha? <a href="/fanbeads/login">Faça login</a>
        </div>
    </main>
</body>
</html>