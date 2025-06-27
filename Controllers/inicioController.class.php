<?php
class inicioController
{
    private PDO $db;
    public function __construct() { $this->db = Conexao::getInstancia(); }

    public function index(): void
    {
        $dao       = new ProdutoDAO($this->db);
        $novidades = $dao->buscarNovidades(3);
        require 'Views/inicio.php';
    }
}
