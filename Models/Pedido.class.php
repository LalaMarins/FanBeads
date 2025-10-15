<?php
class Pedido
{
    private int $id_pedido;
    private int $id_usuario;
    private string $data_pedido;
    private float $valor_total;
    private string $status;

    /** @var array Itens associados a este pedido. */
    private array $itens = [];

    // Getters
    public function getId(): int { return $this->id_pedido; }
    public function getIdUsuario(): int { return $this->id_usuario; }
    public function getDataPedido(): string { return $this->data_pedido; }
    public function getValorTotal(): float { return $this->valor_total; }
    public function getStatus(): string { return $this->status; }
    public function getItens(): array { return $this->itens; }

    // Setters
    public function setId(int $id): void { $this->id_pedido = $id; }
    public function setIdUsuario(int $id): void { $this->id_usuario = $id; }
    public function setDataPedido(string $data): void { $this->data_pedido = $data; }
    public function setValorTotal(float $valor): void { $this->valor_total = $valor; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setItens(array $itens): void { $this->itens = $itens; }
}