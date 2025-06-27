<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<header>
  <div class="brand"><h1>FanBeads</h1></div>
  <nav class="main-menu">
    <div class="menu-left">
      <a href="/fanbeads/">Início</a>
      <span class="pipe">|</span>
      <a href="/fanbeads/produtos">Produtos</a>
      <span class="pipe">|</span>
      <a href="/fanbeads/pulseiras">Pulseiras</a>
      <span class="pipe">|</span>
      <a href="/fanbeads/phonestraps">Phone Straps</a>

      <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
        <span class="pipe">|</span>
        <a href="/fanbeads/produtos/novo">Adicionar Produto</a>
      <?php endif; ?>
    </div>

    <div class="menu-right">
  <a href="carrinho">
    Carrinho (<?= array_sum(array_column($_SESSION['cart'] ?? [], 'quantidade')) ?>)
  </a>
  <span class="pipe">|</span>

  
  <?php if (isset($_SESSION['user'])): ?>
        <span>Olá, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
        <span class="pipe">|</span>
        <a href="/fanbeads/logout">Sair</a>
      <?php else: ?>
        <a href="/fanbeads/login">Login</a>
        <span class="pipe">|</span>
        <a href="/fanbeads/register">Cadastrar</a>
      <?php endif; ?>
    </div>
</header>