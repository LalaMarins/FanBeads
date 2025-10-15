<?php
class CategoriaDAO
{
    public function __construct(private PDO $db) {}

    /**
     * Busca todas as categorias no banco de dados.
     * @return Categoria[] Retorna um array de objetos Categoria.
     */
    public function buscarTodos(): array
    {
        $sql = "SELECT * FROM categoria ORDER BY nome";
        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_CLASS, Categoria::class);
    }

    /**
     * Busca uma categoria específica pelo seu ID.
     * @param int $id O ID da categoria a ser buscada.
     * @return Categoria|null Retorna um objeto Categoria se encontrado, ou null caso contrário.
     */
    public function buscarPorId(int $id): ?Categoria
    {
        $sql = "SELECT * FROM categoria WHERE id_categoria = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Categoria::class);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }
}