<?php 
// Define o título da página
$pageTitle = 'FanBeads – Carrinho'; 

//Inclui o cabeçalho
require 'Views/_header.php'; 
?>

    <main class="cart-page">
        <h1>Carrinho de Compras</h1>

        <?php if (empty($itensCarrinho)): ?>
            <p class="empty-cart-message">Seu carrinho está vazio.</p>
        <?php else: ?>
            <form id="cart-form" action="/fanbeads/carrinho/atualizar" method="POST">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Produto</th>
                            <th>Cor</th>
                            <th>Tamanho</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Remover</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itensCarrinho as $item): ?>
                            <tr data-index="<?= $item['index'] ?>">
                                <td>
                                    <img src="/fanbeads/assets/img/<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                </td>
                                <td><?= htmlspecialchars($item['nome']) ?></td>
                                <td><?= htmlspecialchars($item['cor']) ?></td>
                                <td><?= htmlspecialchars($item['tamanho']) ?></td>
                                <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <input class="quantity-input" type="number" name="quantidade[<?= $item['index'] ?>]" value="<?= $item['quantidade'] ?>" min="0">
                                </td>
                                <td class="subtotal">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                <td>
                                    <button type="button" class="remove-btn" data-index="<?= $item['index'] ?>">X</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

            <div class="cart-actions">
                <div class="cart-total">
                    <strong>Total:</strong>
                    <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                </div>
                <div id="wallet_container"></div>

        </div> <script>
        // Garante que o script só rode depois que a página carregar
        document.addEventListener('DOMContentLoaded', () => {

            // Pega as variáveis do PHP que o Controller enviou
            const publicKey = "APP_USR-0312d128-8f42-4f2c-bd30-d8505f4aa75a";
            const preferenceId = "<?= $preferenceId ?? '' ?>";

            if (publicKey && preferenceId) {
                // Inicializa o SDK do Mercado Pago
                const mp = new MercadoPago(publicKey, {
                    locale: 'pt-BR' // Deixa o pop-up em português
                });

                // Cria o botão de pagamento (Wallet Brick)
                const bricksBuilder = mp.bricks();

                bricksBuilder.create("wallet", "wallet_container", {
                    initialization: {
                        preferenceId: preferenceId,
                        redirectMode: 'self' // Abre na mesma aba (pop-up)
                    },
                    customization: {
                        texts: {
                            valueProp: 'security_details', // Muda o texto (opcional)
                            action: 'pay' // Texto do botão
                        },
                    }
                })
                .catch((error) => console.error('Erro ao renderizar Wallet Brick:', error));

            } else {
                console.log("MP: Chave pública ou ID de preferência não encontrado.");
            }
        });
        </script>


            </div>
        <?php endif; ?>
    </main>
    <?php 
//Inclui o rodapé
require 'Views/_footer.php'; 
?>