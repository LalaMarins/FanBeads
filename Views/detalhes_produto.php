<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FanBeads – Detalhes</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/script.js"></script>
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <?php if (empty($produto)): ?>
    <main class="details-page">
      <p>Produto não encontrado.</p>
    </main>
  <?php else: ?>
    <main class="details-page">
      <div class="details-image">
        <img
          src="assets/img/<?= htmlspecialchars($produto->getImagem()) ?>"
          alt="<?= htmlspecialchars($produto->getNome()) ?>"
        >
      </div>

      <div class="details-info">
        <h1><?= htmlspecialchars($produto->getNome()) ?></h1>
        <p class="details-price">
          R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?>
        </p>
        <p class="details-desc">
          <?= nl2br(htmlspecialchars($produto->getDescricao())) ?>
        </p>

  <form
+   id="detalhes-form"
+   method="POST"
+   action="adicionar_carrinho"
+   class="details-form"
+   novalidate
+ >

  <input type="hidden" name="produto_id" value="<?= $produto->getId() ?>">

  <?php if (!empty($cores)): ?>
  <fieldset>
    <legend>Cor</legend>
    <?php foreach ($cores as $opt): ?>
      <label>
        <input
          type="radio"
          name="cor"
          value="<?= htmlspecialchars($opt->getValor()) ?>"
        >
        <?= htmlspecialchars($opt->getValor()) ?>
      </label>
    <?php endforeach; ?>
  </fieldset>
<?php endif; ?>


<?php if ($categoria->getNome() !== 'Chaveiros'): ?>
    <fieldset class="tamanho-fieldset">
      <legend>Tamanho</legend>
      <?php foreach ([14,15,16,17,18,19,20,21,22] as $tam): ?>
        <label>
          <input
            type="radio"
            name="tamanho"
            value="<?= $tam ?>"
          >
          <?= $tam ?>
        </label>
      <?php endforeach; ?>

      <label class="t-extra">
        <input
          type="radio"
          name="tamanho"
          value="extra"
        > Outro:
        <input
          type="text"
          name="tamanho_extra"
          placeholder="Escreva o tamanho"
        >
      </label>
    </fieldset>
  <?php endif; ?>

  <button type="submit">Comprar</button>
</form>

      </div>
    </main>
  <?php endif; ?>
</body>
</html>
