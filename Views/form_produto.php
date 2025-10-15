<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="/fanbeads/assets/css/style.css">
           <script src="/fanbeads/assets/js/script.js"></script>
</head>
<body>
    <?php require 'Views/menu.php'; ?>

    <main class="form-page">
        <h2>Novo Produto</h2>
        <form action="/fanbeads/produtos/criar" method="POST" enctype="multipart/form-data">

            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4"></textarea>

            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" required>

            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria_id" required>
                <option value="">-- Selecione --</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat->getId() ?>">
                        <?= htmlspecialchars($cat->getNome()) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="imagem">Imagem:</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" required>

            <fieldset>
                <legend>Cores Disponíveis</legend>
                
                <?php if (!empty($coresDisponiveis)): ?>
                    <div class="checkbox-group">
                        <?php foreach ($coresDisponiveis as $opt): ?>
                            <label class="checkbox-label">
                                <input type="checkbox" name="opcoes[]" value="<?= $opt->getId() ?>">
                                <?= htmlspecialchars($opt->getValor()) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                <?php endif; ?>

                <div id="novas-cores-container">
                    <label>Cor Personalizada:</label>
                    <input type="text" name="cor_extra[]" placeholder="Ex: Roxo">
                </div>
               <button type="button" id="add-cor-btn" class="btn btn-secondary">+ Adicionar outra cor</button>
            </fieldset>

            <button type="submit" class="btn btn-primary">Salvar Produto</button>
        </form>
    </main>
    

</body>
</html>