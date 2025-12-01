<?php
class AdminController
{
    private RelatorioDAO $relatorioDAO;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        // Verifica se é ADMIN. Se não for, chuta para o login.
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /fanbeads/login');
            exit;
        }

        $this->relatorioDAO = new RelatorioDAO();
    }

    public function dashboard(): void
    {
        // 1. Busca os dados do banco
        $topProdutos = $this->relatorioDAO->getTopProdutos();
        $vendasMes = $this->relatorioDAO->getVendasPorMes();
        $stats = $this->relatorioDAO->getEstatisticasGerais();

        // 2. Prepara os dados para o Chart.js (Javascript precisa de Arrays separados)
        // Dados para o Gráfico de Top Produtos
        $prodLabels = json_encode(array_column($topProdutos, 'nome'));
        $prodValues = json_encode(array_column($topProdutos, 'total_vendas'));

        // Dados para o Gráfico de Vendas Mensais
        $mesLabels = json_encode(array_column($vendasMes, 'mes'));
        $mesValues = json_encode(array_column($vendasMes, 'total'));

        // 3. Carrega a view
        require 'Views/admin_dashboard.php';
    }
}