<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FanBeads – <?= htmlspecialchars($categoriaNome ?? 'Produtos') ?></title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="product-page">
        <h1><?= htmlspecialchars($categoriaNome ?? 'Produtos') ?></h1>

        <div class="cards">
            <?php if (empty($produtos)): ?>
                <p class="no-products-message">Nenhum produto encontrado nesta categoria.</p>
            <?php else: ?>
                <?php foreach ($produtos as $p): ?>
                    <div class="card">
                        <a href="/fanbeads/detalhes?id=<?= $p->getId() ?>">
                            <img src="/fanbeads/assets/img/<?= htmlspecialchars($p->getImagem()) ?>" alt="<?= htmlspecialchars($p->getNome()) ?>">
                        </a>
                        <h3>
                            <a href="/fanbeads/detalhes?id=<?= $p->getId() ?>">
                                <?= htmlspecialchars($p->getNome()) ?>
                            </a>
                        </h3>
                        <p>R$ <?= number_format($p->getPreco(), 2, ',', '.') ?></p>

                        <?php // Formulário de exclusão para administradores ?>
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <form action="/fanbeads/produtos/excluir" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                <input type="hidden" name="id" value="<?= $p->getId() ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>