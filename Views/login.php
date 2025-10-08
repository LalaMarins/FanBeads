<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FanBeads – Login</title>
  <!-- caminho absoluto para não quebrar em sub-rotas -->
  <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <main class="form-page">
    <h2>Login</h2>
    <form action="/fanbeads/login" method="POST">
      <label>
        Usuário:
        <input type="text" name="username" required>
      </label>

      <label>
        Senha:
        <input type="password" name="senha" required>
      </label>

      <button type="submit">Entrar</button>
    </form>

    <div class="link-alternativo">
  Não tem conta? <a href="/fanbeads/register">Cadastre-se</a>
</div>
  </main>
</body>
</html>
