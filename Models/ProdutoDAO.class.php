<?php

class ProdutoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Conexao::getInstancia();
    }

    /**
     * Mapeia uma linha do banco de dados (em formato de array) para um objeto Produto.
     * É um método auxiliar para evitar repetição de código.
     * @param array $row O array associativo vindo do banco.
     * @return Produto O objeto Produto populado.
     */
    private function mapearProduto(array $row): Produto
    {
        $categoria = new Categoria();
        $categoria->setId((int) $row['categoria_id']);
        $categoria->setNome($row['nome_categoria']);

        $produto = new Produto();
        $produto->setId((int) $row['id_produto']);
        $produto->setNome($row['nome']);
        $produto->setDescricao($row['descricao']);
        $produto->setPreco((float) $row['preco']);
        $produto->setImagem($row['imagem']);
        $produto->setCategoria($categoria);

        return $produto;
    }

    /**
     * Busca todos os produtos no banco de dados.
     * @return Produto[] Retorna um array de objetos Produto.
     */
    public function buscarTodos(): array
    {
        $sql = "SELECT p.*, c.nome AS nome_categoria, c.id_categoria
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id_categoria
                ORDER BY p.nome ASC";
        
        $stmt = $this->db->query($sql);
        
        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = $this->mapearProduto($row);
        }
        return $produtos;
    }

    /**
     * Busca um produto específico pelo seu ID.
     * @return Produto|null Retorna o objeto Produto se encontrado, ou null.
     */
    public function buscarPorId(int $id): ?Produto
    {
        $sql = "SELECT p.*, c.nome AS nome_categoria, c.id_categoria
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id_categoria
                WHERE p.id_produto = :id LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapearProduto($row) : null;
    }

    /**
     * Busca todos os produtos de uma categoria específica.
     * @return Produto[]
     */
    public function buscarPorCategoria(string $nomeCat): array
    {
        $sql = "SELECT p.*, c.nome AS nome_categoria, c.id_categoria
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id_categoria
                WHERE c.nome = :cat";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':cat', $nomeCat, PDO::PARAM_STR);
        $stmt->execute();

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = $this->mapearProduto($row);
        }
        return $produtos;
    }

    /**
     * Busca os produtos mais recentes para a seção "Novidades".
     * @return Produto[]
     */
    public function buscarNovidades(int $limite = 3): array
    {
        $sql = "SELECT p.*, c.nome AS nome_categoria, c.id_categoria
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id_categoria
                ORDER BY p.id_produto DESC LIMIT :lim";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmt->execute();

        $produtos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = $this->mapearProduto($row);
        }
        return $produtos;
    }

    /**
     * Insere um novo produto no banco de dados.
     */
    public function criar(Produto $p): void
    {
        $sql = "INSERT INTO produto (nome, descricao, preco, imagem, categoria_id)
                VALUES (:nome, :desc, :preco, :img, :cat_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $p->getNome());
        $stmt->bindValue(':desc', $p->getDescricao());
        $stmt->bindValue(':preco', $p->getPreco());
        $stmt->bindValue(':img', $p->getImagem());
        $stmt->bindValue(':cat_id', $p->getCategoria()->getId());
        $stmt->execute();
    }

    /**
     * Retorna o ID do último registro inserido no banco de dados.
     */
    public function ultimoIdInserido(): int
    {
        return (int)$this->db->lastInsertId();
    }

    /**
     * Associa uma opção de variação (como uma cor) a um produto.
     */
    public function adicionarOpcao(int $prodId, int $opId): void
    {
        $sql = "INSERT INTO produto_opcao (produto_id, opcao_id) VALUES (:pid, :oid)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pid', $prodId);
        $stmt->bindValue(':oid', $opId);
        $stmt->execute();
    }

    /**
     * Adiciona uma cor personalizada: cria a opção de cor se ela não existir
     * e depois a associa ao produto.
     */
    public function adicionarCorExtra(int $prodId, string $cor): void
    {
        $sqlVerifica = "SELECT id_opcao FROM opcao_variacao WHERE valor = :cor AND variacao_id = 1 LIMIT 1";
        $stmtVerifica = $this->db->prepare($sqlVerifica);
        $stmtVerifica->bindValue(':cor', $cor);
        $stmtVerifica->execute();
        $opcao = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

        if ($opcao) {
            $opId = $opcao['id_opcao'];
        } else {
            $sqlInsere = "INSERT INTO opcao_variacao (variacao_id, valor) VALUES (1, :cor)";
            $stmtInsere = $this->db->prepare($sqlInsere);
            $stmtInsere->bindValue(':cor', $cor);
            $stmtInsere->execute();
            $opId = $this->db->lastInsertId();
        }
        $this->adicionarOpcao($prodId, $opId);
    }

    /**
     * Exclui um produto e suas associações de cores de forma segura usando uma transação.
     * @return bool Retorna true em caso de sucesso, false em caso de erro.
     */
    public function excluir(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt1 = $this->db->prepare("DELETE FROM produto_opcao WHERE produto_id = :id");
            $stmt1->bindValue(':id', $id);
            $stmt1->execute();

            $stmt2 = $this->db->prepare("DELETE FROM produto WHERE id_produto = :id");
            $stmt2->bindValue(':id', $id);
            $stmt2->execute();
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erro ao excluir produto: " . $e->getMessage());
            return false;
        }
    }
}