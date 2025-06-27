<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FanBeads – Início</title>
  <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <section class="carousel">
    <!-- banners estáticos ou dinâmicos -->
    <img src="/fanbeads/assets/img/banner1.jpg" alt="">
    <img src="/fanbeads/assets/img/banner2.jpg" alt="">
    <img src="/fanbeads/assets/img/banner3.jpg" alt="">
  </section>

  <section class="novidades">
    <h2>Novidades</h2>
    <div class="cards">
      <?php foreach($novidades as $p): ?>
        <div class="card">
          <a href="/fanbeads/detalhes?id=<?= $p->getId() ?>">
            <img src="/fanbeads/assets/img/<?= htmlspecialchars($p->getImagem()) ?>"
                 alt="<?= htmlspecialchars($p->getNome()) ?>">
          </a>
          <h3><?= htmlspecialchars($p->getNome()) ?></h3>
          <p>R$ <?= number_format($p->getPreco(),2,',','.') ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</body>
</html>
