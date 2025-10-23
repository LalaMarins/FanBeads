<?php 
// Define o título da página
$pageTitle = 'FanBeads – Login'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
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

            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>

        <div class="link-alternativo">
             Esqueceu sua senha? <a href="/fanbeads/forgot-password">Esqueci minha senha</a>
             <br>
            Não tem conta? <a href="/fanbeads/register">Cadastre-se</a>
        </div>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>