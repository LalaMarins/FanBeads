<?php
class Categoria
{
    private int $id_categoria;
    private string $nome;

    public function __construct() {}

    public function getId(): int
    {
        return $this->id_categoria;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    // <<< AQUI estão os setters que faltavam >>>
    public function setId(int $id): void
    {
        $this->id_categoria = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
}
