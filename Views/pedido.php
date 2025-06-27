<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pedido Feito</title>
  <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <div style="padding: 2rem;">
    <h2>Pedido feito com sucesso!</h2>
    <p><?php echo $mensagem; ?></p>
    <br>
    <a href="/fanbeads">Voltar à loja</a>
</div>


</html>
