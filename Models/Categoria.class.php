<?php
class Categoria
{
    private int $id_categoria;
    private string $nome;

    public function getId(): int
    {
        return $this->id_categoria;
    }

    public function setId(int $id): void
    {
        $this->id_categoria = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
}