<?php
require_once 'Models/Categoria.class.php';

class Produto
{
    private int $id;
    private string $nome;
    private string $descricao;
    private float $preco;
    private string $imagem;
    private Categoria $categoria;

    public function __construct()
    {
        // garante que sempre exista um objeto Categoria
        $this->categoria = new Categoria();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getImagem(): string
    {
        return $this->imagem;
    }

    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function setPreco(float $preco): void
    {
        $this->preco = $preco;
    }

    public function setImagem(string $imagem): void
    {
        $this->imagem = $imagem;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }
}
