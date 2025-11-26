<?php 
// Define o título da página
$pageTitle = 'FanBeads – Início'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>
    <main>
        <section class="novidades">
            <h2>Novidades</h2>
            <div class="cards">
                <?php foreach ($novidades as $p): ?>
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

                        <?php // CÓDIGO NOVO: Botão de exclusão para administradores na Home ?>
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <form action="/fanbeads/produtos/excluir" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');" style="margin-top: 10px;">
                                <input type="hidden" name="id" value="<?= $p->getId() ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.9rem;">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>