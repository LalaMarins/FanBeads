<?php
require_once 'Models/Conexao.class.php';
require_once 'Models/ProdutoDAO.class.php';
require_once 'Models/Produto.class.php';
require_once 'Models/Categoria.class.php';

class produtoController
{
    private ProdutoDAO $dao;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->dao = new ProdutoDAO( Conexao::getInstancia() );
    }

    public function listar(): void
    {
        $produtos      = $this->dao->buscarTodos();
        $categoriaNome = '';
        require 'Views/listar_produtos.php';
    }

    public function listarPulseiras(): void
    {
        $produtos      = $this->dao->buscarPorCategoria('Pulseiras');
        $categoriaNome = 'Pulseiras';
        require 'Views/listar_produtos.php';
    }

    public function listarPhoneStraps(): void
    {
        $produtos      = $this->dao->buscarPorCategoria('Phone Straps');
        $categoriaNome = 'Phone Straps';
        require 'Views/listar_produtos.php';
    }

    public function detalhes(): void
    {
        $id      = intval($_GET['id'] ?? 0);
        $produto = $this->dao->buscarPorId($id);
        if (!$produto) {
            header('Location: /produtos');
            exit;
        }

        // buscar cores para o produto
        require_once 'Models/VariacaoDAO.class.php';
        $varDao = new VariacaoDAO( Conexao::getInstancia() );
        $cores  = $varDao->buscarCoresPorProduto($id);

        $categoria = $produto->getCategoria();
        require 'Views/detalhes_produto.php';
    }

    public function novo(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: login');
            exit;
        }

        // busca categorias para o select
        require_once 'Models/CategoriaDAO.class.php';
        $catDao    = new CategoriaDAO( Conexao::getInstancia() );
        $categorias = $catDao->buscarTodos();

        // busca cores disponíveis para o produto (variações)
        require_once 'Models/VariacaoDAO.class.php';
        $varDao            = new VariacaoDAO( Conexao::getInstancia() );
        $coresDisponiveis  = $varDao->buscarTodasCores();

        require 'Views/form_produto.php';
    }

    public function criar(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: login');
            exit;
        }

        $p = new Produto();
        $p->setNome(trim($_POST['nome']));
        $p->setDescricao(trim($_POST['descricao']));
        $p->setPreco((float) $_POST['preco']);

        $cat = new Categoria();
        $cat->setId(intval($_POST['categoria_id']));
        $p->setCategoria($cat);

        $orig = basename($_FILES['imagem']['name']);
        $ext  = pathinfo($orig, PATHINFO_EXTENSION);
        $novo = uniqid('img_') . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], 'assets/img/' . $novo);
        $p->setImagem($novo);

        $this->dao->criar($p);
        $produtoId = $this->dao->ultimoIdInserido();

        foreach ($_POST['opcoes'] ?? [] as $opId) {
            $this->dao->adicionarOpcao($produtoId, intval($opId));
        }
        foreach (array_filter(array_map('trim', $_POST['cor_extra'] ?? [])) as $cor) {
            $this->dao->adicionarCorExtra($produtoId, $cor);
        }


        header('Location: /fanbeads/produtos');        
        exit;
    }
    public function excluir() {
    
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
    
            $dao = new ProdutoDAO();
            $dao->excluir($id);
    
            header("Location: /fanbeads/produtos");
            exit;
        }
    }
    

}
