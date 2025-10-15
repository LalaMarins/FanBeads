<?php
class OpcaoVariacao
{
    private int $id_opcao;
    private int $variacao_id;
    private string $valor;

    // Getters
    public function getId(): int
    {
        return $this->id_opcao;
    }

    public function getVariacaoId(): int
    {
        return $this->variacao_id;
    }

    public function getValor(): string
    {
        return $this->valor;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id_opcao = $id;
    }

    public function setVariacaoId(int $vid): void
    {
        $this->variacao_id = $vid;
    }

    public function setValor(string $valor): void
    {
        $this->valor = $valor;
    }
}