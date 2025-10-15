<?php
class VariacaoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexao::getInstancia();
    }

    /**
     * Retorna todas as opções de variação cadastradas (ex: todas as cores).
     * @return OpcaoVariacao[]
     */
    public function buscarTodasCores(): array
    {
        $sql = "SELECT id_opcao, variacao_id, valor FROM opcao_variacao ORDER BY valor";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_CLASS, OpcaoVariacao::class);
    }

    /**
     * Retorna as opções de variação (cores) vinculadas a um produto específico.
     * @return OpcaoVariacao[]
     */
    public function buscarCoresPorProduto(int $produtoId): array
    {
        $sql = "SELECT o.id_opcao, o.variacao_id, o.valor
                FROM opcao_variacao o
                INNER JOIN produto_opcao po ON po.opcao_id = o.id_opcao
                WHERE po.produto_id = :pid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $produtoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, OpcaoVariacao::class);
    }
}