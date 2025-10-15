<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="order-history-page">
        <h1>Meus Pedidos</h1>

        <?php if (empty($pedidos)): ?>
            <p class="no-orders-message">Você ainda não fez nenhum pedido.</p>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="header-info">
                            <h3>Pedido #<?= $pedido->getId() ?></h3>
                            <span>Feito em: <?= date('d/m/Y', strtotime($pedido->getDataPedido())) ?></span>
                        </div>
                        <div class="header-total">
                            <strong>Total: R$ <?= number_format($pedido->getValorTotal(), 2, ',', '.') ?></strong>
                            <span class="status status-<?= strtolower($pedido->getStatus()) ?>"><?= htmlspecialchars($pedido->getStatus()) ?></span>
                        </div>
                    </div>
                    <div class="order-card-body">
                        <?php foreach ($pedido->getItens() as $item): ?>
                            <div class="history-item">
                                <img src="/fanbeads/assets/img/<?= htmlspecialchars($item['imagem_produto']) ?>" alt="<?= htmlspecialchars($item['nome_produto']) ?>">
                                <div class="item-details">
                                    <span class="item-name"><?= htmlspecialchars($item['nome_produto']) ?></span>
                                    <span class="item-options">
                                        <?= $item['quantidade'] ?>x | Cor: <?= htmlspecialchars($item['cor']) ?> | Tamanho: <?= htmlspecialchars($item['tamanho']) ?>
                                    </span>
                                </div>
                                <span class="item-price">R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>