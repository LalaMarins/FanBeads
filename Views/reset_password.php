<?php 
// Define o título da página
$pageTitle = 'FanBeads – Redefinir Senha'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
    <main class="form-page">
        <h2>Redefinir Senha</h2>

        <?php // --- BLOCO DE MENSAGENS --- ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <div class="link-alternativo">
                <a href="/fanbeads/forgot-password">Solicitar um novo link</a>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
            <div class="link-alternativo">
                <a href="/fanbeads/login">Fazer Login</a>
            </div>
        <?php endif; ?>
        <?php // --- FIM DO BLOCO --- ?>


        <?php // Só exibe o formulário se a variável $token for válida (controlado pelo Controller) ?>
        <?php if (isset($token) && !isset($success)): ?>
            <p>Digite sua nova senha. Ela deve ter pelo menos 6 caracteres.</p>
            
            <form action="/fanbeads/reset-password" method="POST">
                <?php // O token é enviado secretamente junto com o formulário ?>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label for="senha">Nova Senha:</label>
                <input type="password" id="senha" name="senha" required minlength="6">

                <label for="senha_confirm">Confirmar Nova Senha:</label>
                <input type="password" id="senha_confirm" name="senha_confirm" required minlength="6">

                <button type="submit" class="btn btn-primary">Redefinir Senha</button>
            </form>
        <?php endif; ?>

    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>