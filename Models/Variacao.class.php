<?php
class Variacao
{
    private int $id_variacao;
    private string $nome;

    // Getters
    public function getId(): int { return $this->id_variacao; }
    public function getNome(): string { return $this->nome; }

    // Setters
    public function setId(int $id): void { $this->id_variacao = $id; }
    public function setNome(string $nome): void { $this->nome = $nome; }
}