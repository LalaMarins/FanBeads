<?php
class RelatorioDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexao::getInstancia();
    }

    /**
     * Retorna os 5 produtos mais vendidos (nome e quantidade total).
     */
    public function getTopProdutos(int $limite = 5): array
    {
        $sql = "SELECT p.nome, SUM(pi.quantidade) as total_vendas 
                FROM pedido_itens pi 
                JOIN produto p ON pi.id_produto = p.id_produto 
                GROUP BY pi.id_produto 
                ORDER BY total_vendas DESC 
                LIMIT :limite";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna o faturamento total agrupado por mês (últimos 6 meses).
     */
    public function getVendasPorMes(): array
    {
        // Pega o faturamento dos últimos 6 meses
        $sql = "SELECT DATE_FORMAT(data_pedido, '%m/%Y') as mes, SUM(valor_total) as total 
                FROM pedidos 
                GROUP BY DATE_FORMAT(data_pedido, '%Y-%m') 
                ORDER BY data_pedido ASC 
                LIMIT 6";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna estatísticas gerais simples (cards do topo).
     */
    public function getEstatisticasGerais(): array
    {
        $stats = [];

        // Total de Vendas (R$)
        $sql1 = "SELECT SUM(valor_total) as total FROM pedidos";
        $stats['faturamento_total'] = $this->db->query($sql1)->fetchColumn() ?: 0;

        // Total de Pedidos Feitos
        $sql2 = "SELECT COUNT(*) FROM pedidos";
        $stats['total_pedidos'] = $this->db->query($sql2)->fetchColumn() ?: 0;

        // Total de Produtos Cadastrados
        $sql3 = "SELECT COUNT(*) FROM produto";
        $stats['total_produtos'] = $this->db->query($sql3)->fetchColumn() ?: 0;

        return $stats;
    }
}