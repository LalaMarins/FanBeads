<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FanBeads – Cadastro</title>
  <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <main class="form-page">
    <h2>Cadastre-se</h2>
    <form action="/fanbeads/cadastrar" method="POST">
      <label>
        Usuário:
        <input type="text" name="username" required>
      </label>

      <label>
        E-mail:
        <input type="email" name="email" required>
      </label>

      <label>
        Senha:
        <input type="password" name="senha" required>
      </label>

      <button type="submit">Criar Conta</button>
    </form>

    <div class="link-alternativo">
  Já tem conta? <a href="/fanbeads/login">Faça login</a>
</div>
  </main>
</body>
</html>
