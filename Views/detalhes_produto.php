<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FanBeads – <?= htmlspecialchars($produto->getNome() ?? 'Detalhes') ?></title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="details-page">
        <?php if (empty($produto)): ?>
            <p>Produto não encontrado.</p>
        <?php else: ?>
            <div class="details-image">
                <img src="/fanbeads/assets/img/<?= htmlspecialchars($produto->getImagem()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
            </div>

            <div class="details-info">
                <h1><?= htmlspecialchars($produto->getNome()) ?></h1>
                <p class="details-price">
                    R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?>
                </p>
                <div class="details-desc">
                    <?= nl2br(htmlspecialchars($produto->getDescricao())) ?>
                </div>

                <form id="detalhes-form" method="POST" action="/fanbeads/carrinho/adicionar" class="details-form" novalidate>
                    <input type="hidden" name="produto_id" value="<?= $produto->getId() ?>">

                    <?php if (!empty($cores)): ?>
                        <fieldset>
                            <legend>Cor</legend>
                            <?php foreach ($cores as $opt): ?>
                                <label>
                                    <input type="radio" name="cor" value="<?= htmlspecialchars($opt->getValor()) ?>" required>
                                    <?= htmlspecialchars($opt->getValor()) ?>
                                </label>
                            <?php endforeach; ?>
                        </fieldset>
                    <?php endif; ?>

                    <?php if ($categoria->getNome() !== 'Chaveiros'): ?>
                        <fieldset class="tamanho-fieldset">
                            <legend>Tamanho (cm)</legend>
                            <?php foreach ([14, 15, 16, 17, 18, 19, 20, 21, 22] as $tam): ?>
                                <label>
                                    <input type="radio" name="tamanho" value="<?= $tam ?>" required>
                                    <?= $tam ?>
                                </label>
                            <?php endforeach; ?>
                            <label class="t-extra">
                                <input type="radio" name="tamanho" value="extra" required>
                                Outro:
                                <input type="text" name="tamanho_extra" placeholder="Ex: 15.5">
                            </label>
                        </fieldset>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
                </form>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>