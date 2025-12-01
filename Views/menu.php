<?php
// Garante que a sessão seja iniciada antes de qualquer HTML ser enviado.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Calcula o número total de itens no carrinho de forma segura.
$totalItensCarrinho = 0;
if (!empty($_SESSION['cart'])) {
    $totalItensCarrinho = array_sum(array_column($_SESSION['cart'], 'quantidade'));
}
?>
<header>
    <div class="brand">
        <a href="/fanbeads/">
            <h1>Fan<span>Beads</span></h1>
        </a>
    </div>
    <nav class="main-menu">
        <div class="menu-left">
            <a href="/fanbeads/">Início</a>
            <span class="pipe">|</span>
            <a href="/fanbeads/produtos">Produtos</a>
            <span class="pipe">|</span>
            <a href="/fanbeads/pulseiras">Pulseiras</a>
            <span class="pipe">|</span>
            <a href="/fanbeads/chaveiros">Chaveiros</a>

            <?php // Mostra links de admin apenas se o usuário for um administrador ?>
            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                <span class="pipe">|</span>
                <a href="/fanbeads/admin/dashboard">★ Dashboard</a>
                <span class="pipe">|</span>
                <a href="/fanbeads/produtos/novo">Adicionar Produto</a>
            <?php endif; ?>
        </div>

        <div class="menu-right">
            <a href="/fanbeads/carrinho">Carrinho (<?= $totalItensCarrinho ?>)</a>
            <span class="pipe">|</span>

            <?php // Menu para usuários logados ?>
            <?php if (isset($_SESSION['user'])): ?>
                <span>Olá, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <span class="pipe">|</span>
                <a href="/fanbeads/meus-pedidos">Meus Pedidos</a>
                <span class="pipe">|</span>
                <a href="/fanbeads/logout">Sair</a>
            <?php // Menu para visitantes ?>
            <?php else: ?>
                <a href="/fanbeads/login">Login</a>
                <span class="pipe">|</span>
                <a href="/fanbeads/register">Cadastrar</a>
            <?php endif; ?>
        </div>
    </nav>
</header>