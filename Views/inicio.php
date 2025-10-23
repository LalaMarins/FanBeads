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
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>