<?php 
// Define o título da página
$pageTitle = 'FanBeads – Recuperar Senha'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
    <main class="form-page">
        <h2>Recuperar Senha</h2>
        <p>Digite seu e-mail abaixo. Se ele estiver cadastrado, enviaremos instruções para redefinir sua senha.</p>
<?php // --- BLOCO DE MENSAGENS ATUALIZADO --- ?>
    <?php // Mensagem de sucesso quando o email É encontrado ?>
<?php if (isset($_GET['success']) && $_GET['success'] === 'instrucoes_enviadas'): ?>
    <p class="success-message">Se o e-mail estiver cadastrado, você receberá as instruções em breve.</p>
<?php endif; ?>

    <?php // Mensagem de erro quando o email NÃO é encontrado ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_nao_encontrado'): ?>
        <p class="error-message">Este endereço de e-mail não foi encontrado em nosso sistema.</p>
    <?php endif; ?>

    <?php // Mensagem de erro para email inválido ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_invalido'): ?>
        <p class="error-message">Por favor, digite um endereço de e-mail válido.</p>
    <?php endif; ?>
    <?php // --- FIM DO BLOCO DE MENSAGENS --- ?>

        <form action="/fanbeads/forgot-password" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit" class="btn btn-primary">Enviar Instruções</button>
        </form>

        <div class="link-alternativo">
            Lembrou a senha? <a href="/fanbeads/login">Faça login</a>
        </div>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>