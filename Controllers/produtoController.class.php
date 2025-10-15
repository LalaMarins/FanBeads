<?php
class ProdutoController
{
    private ProdutoDAO $produtoDAO;
    private CategoriaDAO $categoriaDAO;
    private VariacaoDAO $variacaoDAO;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Instancia todos os DAOs necessários uma única vez.
        $this->produtoDAO = new ProdutoDAO();
        $this->categoriaDAO = new CategoriaDAO(Conexao::getInstancia()); // Mantém injeção se preferir
        $this->variacaoDAO = new VariacaoDAO();
    }

    /**
     * Lista todos os produtos de todas as categorias.
     */
    public function listar(): void
    {
        $produtos = $this->produtoDAO->buscarTodos();
        $categoriaNome = 'Todos os Produtos'; // Título para a página
        require 'Views/listar_produtos.php';
    }

    /**
     * Lista apenas os produtos da categoria 'Pulseiras'.
     */
    public function listarPulseiras(): void
    {
        $produtos = $this->produtoDAO->buscarPorCategoria('Pulseiras');
        $categoriaNome = 'Pulseiras';
        require 'Views/listar_produtos.php';
    }

    /**
     * Lista apenas os produtos da categoria 'Chaveiros'.
     */
    public function listarChaveiros(): void
    {
        $produtos = $this->produtoDAO->buscarPorCategoria('Chaveiros');
        $categoriaNome = 'Chaveiros';
        require 'Views/listar_produtos.php';
    }

    /**
     * Exibe a página de detalhes de um produto específico.
     */
    public function detalhes(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        // Validação: se o ID for inválido, redireciona para a página de produtos.
        if ($id <= 0) {
            header('Location: /fanbeads/produtos');
            exit;
        }

        $produto = $this->produtoDAO->buscarPorId($id);
        
        // Se o produto não for encontrado no banco, redireciona.
        if (!$produto) {
            header('Location: /fanbeads/produtos');
            exit;
        }

        // Busca as cores (variações) associadas a este produto.
        $cores = $this->variacaoDAO->buscarCoresPorProduto($id);
        $categoria = $produto->getCategoria();
        
        require 'Views/detalhes_produto.php';
    }

    /**
     * (Admin) Exibe o formulário para criar um novo produto.
     */
    public function novo(): void
    {
        $this->checkAdmin(); // Centraliza a verificação de permissão

        // Busca dados necessários para preencher os selects e checkboxes do formulário.
        $categorias = $this->categoriaDAO->buscarTodos();
        $coresDisponiveis = $this->variacaoDAO->buscarTodasCores();

        require 'Views/form_produto.php';
    }

    /**
     * (Admin) Processa os dados do formulário para criar um novo produto.
     */
    public function criar(): void
    {
        $this->checkAdmin();

        $produto = new Produto();
        $produto->setNome(trim($_POST['nome']));
        $produto->setDescricao(trim($_POST['descricao']));
        $produto->setPreco((float) $_POST['preco']);

        $categoria = new Categoria();
        $categoria->setId((int)$_POST['categoria_id']);
        $produto->setCategoria($categoria);

        // Processa o upload da imagem de forma segura.
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $origName = basename($_FILES['imagem']['name']);
            $extension = pathinfo($origName, PATHINFO_EXTENSION);
            $newName = uniqid('img_') . '.' . $extension;
            move_uploaded_file($_FILES['imagem']['tmp_name'], 'assets/img/' . $newName);
            $produto->setImagem($newName);
        }

        // Salva o produto principal e obtém seu ID.
        $this->produtoDAO->criar($produto);
        $produtoId = $this->produtoDAO->ultimoIdInserido();

        // Associa as cores selecionadas via checkbox.
        foreach ($_POST['opcoes'] ?? [] as $opId) {
            $this->produtoDAO->adicionarOpcao($produtoId, (int)$opId);
        }
        
        // Processa e salva as novas cores inseridas nos campos de texto.
        $coresExtras = array_filter(array_map('trim', $_POST['cor_extra'] ?? []));
        foreach ($coresExtras as $cor) {
            $this->produtoDAO->adicionarCorExtra($produtoId, $cor);
        }

        header('Location: /fanbeads/produtos');        
        exit;
    }

    /**
     * (Admin) Processa a exclusão de um produto.
     */
    public function excluir(): void
    {
        $this->checkAdmin();
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->produtoDAO->excluir($id);
        }
    
        header("Location: /fanbeads/produtos");
        exit;
    }

    /**
     * Método auxiliar para verificar se o usuário é um administrador.
     * Evita repetição de código nos métodos de admin.
     */
    private function checkAdmin(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /fanbeads/login');
            exit;
        }
    }
}