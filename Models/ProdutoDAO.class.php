<?php
require_once 'Models/Conexao.class.php';
require_once 'Models/Produto.class.php';
require_once 'Models/Categoria.class.php';
require_once 'Models/OpcaoVariacao.class.php';

class ProdutoDAO
{
    private PDO $db;

    public function __construct()
    {
        // Conexão singleton
        $this->db = Conexao::getInstancia();
    }

    /**
     * Mapeia uma linha para um objeto Produto
     */
    private function mapearProduto(array $row): Produto
    {
        $cat = new Categoria();
        $cat->setId((int) $row['id_categoria']);
        $cat->setNome($row['nome_categoria']);

        $p = new Produto();
        $p->setId((int) $row['id_produto']);
        $p->setNome($row['nome']);
        $p->setDescricao($row['descricao']);
        $p->setPreco((float) $row['preco']);
        $p->setImagem($row['imagem']);
        $p->setCategoria($cat);

        return $p;
    }

    /**
     * Mapeia resultado para lista de Produto
     */
    private function mapearLista(PDOStatement $stmt): array
    {
        $lista = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lista[] = $this->mapearProduto($row);
        }
        return $lista;
    }

    public function buscarTodos(): array
    {
        $sql = "
          SELECT
            p.id_produto, p.nome, p.descricao, p.preco, p.imagem,
            c.id_categoria, c.nome AS nome_categoria
          FROM produto p
          JOIN categoria c ON p.categoria_id = c.id_categoria
        ";
        $stmt = $this->db->query($sql);
        return $this->mapearLista($stmt);
    }

    public function buscarPorId(int $id): ?Produto
    {
        $sql = "
          SELECT
            p.id_produto, p.nome, p.descricao, p.preco, p.imagem,
            c.id_categoria, c.nome AS nome_categoria
          FROM produto p
          JOIN categoria c ON p.categoria_id = c.id_categoria
          WHERE p.id_produto = :id
          LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapearProduto($row) : null;
    }

    public function buscarPorCategoria(string $nomeCat): array
    {
        $sql = "
          SELECT
            p.id_produto, p.nome, p.descricao, p.preco, p.imagem,
            c.id_categoria, c.nome AS nome_categoria
          FROM produto p
          JOIN categoria c ON p.categoria_id = c.id_categoria
          WHERE c.nome = :cat
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat', $nomeCat, PDO::PARAM_STR);
        $stmt->execute();
        return $this->mapearLista($stmt);
    }

    public function buscarNovidades(int $limite = 3): array
    {
        $sql = "
          SELECT
            p.id_produto, p.nome, p.descricao, p.preco, p.imagem,
            c.id_categoria, c.nome AS nome_categoria
          FROM produto p
          JOIN categoria c ON p.categoria_id = c.id_categoria
          ORDER BY p.id_produto DESC
          LIMIT :lim
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $this->mapearLista($stmt);
    }

    /**
     * Retorna cores de um produto
     */
    public function buscarCoresPorProduto(int $produtoId): array
    {
        $sql = "
          SELECT
            o.id_opcao, o.variacao_id, o.valor
          FROM opcao_variacao o
          JOIN produto_opcao po ON po.opcao_id = o.id_opcao
          WHERE po.produto_id = :pid
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $produtoId, PDO::PARAM_INT);
        $stmt->execute();

        $cores = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cores[] = new OpcaoVariacao(
                (int)$r['id_opcao'], 
                (int)$r['variacao_id'], 
                $r['valor']
            );
        }
        return $cores;
    }

    public function criar(Produto $p): void
    {
        $sql = "
          INSERT INTO produto
            (nome, descricao, preco, imagem, categoria_id)
          VALUES
            (:n, :d, :pr, :im, :cat)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':n',   $p->getNome(),         PDO::PARAM_STR);
        $stmt->bindValue(':d',   $p->getDescricao(),    PDO::PARAM_STR);
        $stmt->bindValue(':pr',  $p->getPreco(),        PDO::PARAM_STR);
        $stmt->bindValue(':im',  $p->getImagem(),       PDO::PARAM_STR);
        $stmt->bindValue(':cat', $p->getCategoria()->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function ultimoIdInserido(): int
    {
        return (int)$this->db->lastInsertId();
    }

    public function adicionarOpcao(int $prodId, int $opId): void
    {
        $sql = "INSERT INTO produto_opcao (produto_id, opcao_id) VALUES (:p, :o)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':p', $prodId, PDO::PARAM_INT);
        $stmt->bindValue(':o', $opId,   PDO::PARAM_INT);
        $stmt->execute();
    }

    public function adicionarCorExtra(int $prodId, string $cor): void
    {
        $sql = "INSERT INTO produto_cor (produto_id, cor) VALUES (:p, :c)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':p', $prodId, PDO::PARAM_INT);
        $stmt->bindValue(':c', $cor,    PDO::PARAM_STR);
        $stmt->execute();
    }

    public function excluir($id) {
      $sql1 = "DELETE FROM produto_opcao WHERE produto_id = :id";
      $sql2 = "DELETE FROM produto WHERE id_produto = :id";
  
      $stmt1 = $this->db->prepare($sql1);
      $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt1->execute();
  
      $stmt2 = $this->db->prepare($sql2);
      $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt2->execute();
  }
  

}
