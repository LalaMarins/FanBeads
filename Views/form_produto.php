<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Adicionar Produto</title>
  <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
</head>
<body>
  <?php require 'Views/menu.php'; ?>

  <main class="form-page">
    <h2>Novo Produto</h2>
    <form action="/fanbeads/produtos/novo" method="POST" enctype="multipart/form-data">

      <label>Nome:<br>
        <input type="text" name="nome" required>
      </label><br><br>

      <label>Descrição:<br>
        <textarea name="descricao" rows="4"></textarea>
      </label><br><br>

      <label>Preço:<br>
        <input type="number" step="0.01" name="preco" required>
      </label><br><br>

      <label>Categoria:<br>
        <select name="categoria_id" required>
          <option value="">-- selecione --</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat->getId() ?>">
              <?= htmlspecialchars($cat->getNome()) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label><br><br>

      <label>Imagem:<br>
        <input type="file" name="imagem" accept="image/*" required>
      </label><br><br>

      <fieldset>
        <legend>Cores disponíveis</legend>
        <?php if (!empty($coresDisponiveis)): ?>
          <?php foreach ($coresDisponiveis as $opt): ?>
            <label style="margin-right:10px;">
              <input type="checkbox" name="opcoes[]" value="<?= $opt->getId() ?>">
              <?= htmlspecialchars($opt->getValor()) ?>
            </label>
          <?php endforeach; ?>
          <hr style="margin:1em 0;">
        <?php endif; ?>

        <div id="novas-cores-container">
          <label>
            <input type="text" name="cor_extra[]" placeholder="Escreva uma nova cor">
          </label>
        </div>
        <button type="button" id="add-cor-btn">+ Adicionar outra cor</button>
      </fieldset><br>

      <button type="submit">Salvar Produto</button>
    </form>
  </main>

  <script>
    document.getElementById('add-cor-btn').addEventListener('click', () => {
      const container = document.getElementById('novas-cores-container');
      const label = document.createElement('label');
      label.innerHTML = '<input type=\"text\" name=\"cor_extra[]\" placeholder=\"Escreva uma nova cor\">';
      container.appendChild(label);
    });
  </script>
</body>
</html>
