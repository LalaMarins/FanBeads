<?php 
// Define o título da página
$pageTitle = 'FanBeads – Cadastro'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
    <main class="form-page">
        <h2>Cadastre-se</h2>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] === 'email_existente'): ?>
                <p class="error-message">Este e-mail já está cadastrado.</p>
            <?php elseif ($_GET['error'] === 'usuario_existente'): ?>
                <p class="error-message">Este nome de usuário já está em uso.</p>
            <?php elseif ($_GET['error'] === 'campos_vazios'): ?>
                <p class="error-message">Por favor, preencha todos os campos.</p>
            <?php endif; ?>
        <?php endif; ?>
        <form action="/fanbeads/register" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Senha:</label>
            <input type="password" id="password" name="senha" required>

            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>

        <div class="link-alternativo">
            Já tem conta? <a href="/fanbeads/login">Faça login</a>
        </div>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>