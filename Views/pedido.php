<?php 
// Define o título da página
$pageTitle = 'FanBeads – Pedido Feito'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
    <main class="order-confirmation-page">
        <div class="order-header">
            <h2>Obrigado pelo seu pedido!</h2>
            <p>Seu pedido <strong>#<?= $numero_pedido ?></strong> foi finalizado com sucesso.</p>
        </div>

        <div class="order-summary">
            <h3>Resumo da Compra</h3>
            <ul class="order-summary-list">
                <?php foreach ($itens_pedido as $item): ?>
                    <li class="order-item">
                        <img class="item-image" src="/fanbeads/assets/img/<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                        <div class="item-details">
                            <span class="item-name"><?= htmlspecialchars($item['nome']) ?></span>
                            <span class="item-qty">Quantidade: <?= $item['quantidade'] ?></span>
                            <span class="item-options">Cor: <?= htmlspecialchars($item['cor']) ?> | Tamanho: <?= htmlspecialchars($item['tamanho']) ?></span>
                        </div>
                        <span class="item-subtotal">
                            R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="order-total">
            <span>Total</span>
            <strong>R$ <?= number_format($total_pedido, 2, ',', '.') ?></strong>
        </div>

        <div class="order-footer">
            <p>Você receberá um e-mail com os detalhes da sua compra em breve.</p>
            <a href="/fanbeads/" class="btn-back-to-store">Voltar à Loja</a>
        </div>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>