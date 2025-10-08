<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FanBeads – Carrinho</title>
  <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
  <style>
    .cart-table img { width: 50px; border-radius: 4px; }
    .remove-btn { background: #ff5252; color: #fff; border: none; padding: 4px 8px; cursor: pointer; }
    .remove-btn:hover { opacity: 0.8; }
    .quantity-input { width: 60px; }
  </style>
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <main class="cart-page">
    <h1>Carrinho</h1>

    <?php if (empty($items)): ?>
      <p>Seu carrinho está vazio.</p>
    <?php else: ?>
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
          <?php foreach ($items as $item): ?>
            <tr data-index="<?= $item['index'] ?>">
              <td>
                <img src="/fanbeads/assets/img/<?= htmlspecialchars($item['imagem']) ?>"
                     alt="<?= htmlspecialchars($item['nome']) ?>">
              </td>
              <td><?= htmlspecialchars($item['nome']) ?></td>
              <td><?= htmlspecialchars($item['cor']) ?></td>
              <td><?= htmlspecialchars($item['tamanho']) ?></td>
              <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
              <td>
                <input
                  class="quantity-input"
                  type="number"
                  min="0"
                  value="<?= $item['quantidade'] ?>"
                  + data-action="carrinho/atualizar"
                  data-index="<?= $item['index'] ?>"
                >
              </td>
              <td class="subtotal">R$ <?= number_format($item['subtotal'],2,',','.') ?></td>
              <td>
                <button
                  class="remove-btn"
                  data-action="carrinho/remover"
                  data-index="<?= $item['index'] ?>"
                >X</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

       <div class="cart-actions">
      <div class="cart-total">
        <strong>Total:</strong>
        <span id="cart-total">R$ <?= number_format($total,2,',','.') ?></span>
      </div>
       <a href="/fanbeads/pedido/feito" class="btn-finalizar">Finalizar Pedido</a>
    </div>
    <?php endif; ?>
  </main>

  <script>
  // Utility: post formData to given URL
  async function postForm(url, data) {
    const resp = await fetch(url, {
      method: 'POST',
      body: data
    });
    if (!resp.ok) throw new Error(resp.statusText);
    return resp;
  }

  // Atualizar quantidade automaticamente
  document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', async () => {
      const idx = input.dataset.index;
      const qty = input.value;
      const url = input.dataset.action;
      const formData = new FormData();
      formData.append(`quantidade[${idx}]`, qty);

      try {
        await postForm(url, formData);
        // recarrega a página para refletir subtotais e total
        window.location.reload();
      } catch (e) {
        alert('Erro ao atualizar quantidade');
        console.error(e);
      }
    });
  });

  // Remover item automaticamente
  document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
      const idx = btn.dataset.index;
      const url = btn.dataset.action;
      const formData = new FormData();
      formData.append('index', idx);

      try {
        await postForm(url, formData);
        // remove a linha da tabela imediatamente
        document.querySelector(`tr[data-index="${idx}"]`).remove();
        // recarrega para atualizar total
        window.location.reload();
      } catch (e) {
        alert('Erro ao remover item');
        console.error(e);
      }
    });
  });
  </script>
</body>
</html>
