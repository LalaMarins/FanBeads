<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FanBeads – Login</title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="form-page">
        <h2>Login</h2>

        <?php // Exibe uma mensagem de erro se o login falhar ?>
        <?php if (isset($loginError)): ?>
            <p class="error-message"><?= htmlspecialchars($loginError) ?></p>
        <?php endif; ?>
        
        <?php // Exibe uma mensagem de sucesso após o cadastro ?>
        <?php if (isset($_GET['success']) && $_GET['success'] === 'cadastro_ok'): ?>
            <p class="success-message">Cadastro realizado com sucesso! Faça o login para continuar.</p>
        <?php endif; ?>

        <form action="/fanbeads/login" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <div class="link-alternativo">
            Não tem conta? <a href="/fanbeads/register">Cadastre-se</a>
        </div>
    </main>
</body>
</html>