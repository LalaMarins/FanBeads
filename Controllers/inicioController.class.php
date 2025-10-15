<?php
class InicioController
{
    /** @var ProdutoDAO A instância do Data Access Object para produtos. */
    private ProdutoDAO $produtoDAO;

    public function __construct()
    {
        // Instancia o DAO no construtor para ser usado pelos métodos do controller.
        $this->produtoDAO = new ProdutoDAO();
    }

    /**
     * Monta e exibe a página inicial.
     * Busca os produtos mais recentes (novidades) e os envia para a view.
     */
    public function index(): void
    {
        // Busca os 3 produtos mais recentes para a seção "Novidades".
        $novidades = $this->produtoDAO->buscarNovidades(3);

        // Carrega o arquivo da view para renderizar a página.
        require 'Views/inicio.php';
    }
}