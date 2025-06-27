<?php
class CategoriaDAO
{
    public function __construct(private PDO $db) {}

    public function buscarTodos(): array
    {
        $sql = "SELECT * FROM categoria ORDER BY nome";
        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_CLASS, Categoria::class);
    }

    public function buscarPorId(int $id): ?Categoria
    {
        $sql = "SELECT * FROM categoria WHERE id_categoria = :i LIMIT 1";
        $stm = $this->db->prepare($sql);
        $stm->bindValue(':i',$id,PDO::PARAM_INT);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_CLASS,Categoria::class);
        return $stm->fetch() ?: null;
    }
}
