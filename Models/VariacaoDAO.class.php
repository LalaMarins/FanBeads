<?php
require_once 'Models/Conexao.class.php';
require_once 'Models/OpcaoVariacao.class.php';

class VariacaoDAO
{
    private PDO $db;

    public function __construct(PDO $db = null)
    {
        // usa o singleton se nenhum PDO for passado
        $this->db = $db ?? Conexao::getInstancia();
    }

    /**
     * Retorna todas as cores (opções de variação) cadastradas
     * @return OpcaoVariacao[]
     */
    public function buscarTodasCores(): array
    {
        $sql = "
          SELECT id_opcao, variacao_id, valor
          FROM opcao_variacao
          ORDER BY valor
        ";
        $stmt = $this->db->query($sql);
        $cores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cores[] = new OpcaoVariacao(
                (int)$row['id_opcao'], 
                (int)$row['variacao_id'], 
                $row['valor']
            );
        }
        return $cores;
    }

    /**
     * Retorna as cores vinculadas a um produto específico
     * @param int $produtoId
     * @return OpcaoVariacao[]
     */
    public function buscarCoresPorProduto(int $produtoId): array
    {
        $sql = "
          SELECT o.id_opcao, o.variacao_id, o.valor
          FROM opcao_variacao o
          INNER JOIN produto_opcao po ON po.opcao_id = o.id_opcao
          WHERE po.produto_id = :pid
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $produtoId, PDO::PARAM_INT);
        $stmt->execute();

        $cores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cores[] = new OpcaoVariacao(
                (int)$row['id_opcao'], 
                (int)$row['variacao_id'], 
                $row['valor']
            );
        }
        return $cores;
    }
}
