<?php
class PedidoItem
{
    private int $id_item;
    private int $id_pedido;
    private int $id_produto;
    private int $quantidade;
    private float $preco_unitario;
    private string $cor;
    private string $tamanho;

    // Getters
    public function getId(): int { return $this->id_item; }
    public function getIdPedido(): int { return $this->id_pedido; }
    public function getIdProduto(): int { return $this->id_produto; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->preco_unitario; }
    public function getCor(): string { return $this->cor; }
    public function getTamanho(): string { return $this->tamanho; }

    // Setters
    public function setId(int $id): void { $this->id_item = $id; }
    public function setIdPedido(int $id): void { $this->id_pedido = $id; }
    public function setIdProduto(int $id): void { $this->id_produto = $id; }
    public function setQuantidade(int $qtd): void { $this->quantidade = $qtd; }
    public function setPrecoUnitario(float $preco): void { $this->preco_unitario = $preco; }
    public function setCor(string $cor): void { $this->cor = $cor; }
    public function setTamanho(string $tamanho): void { $this->tamanho = $tamanho; }
}