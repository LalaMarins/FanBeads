<?php
class OpcaoVariacao
{
    public int    $id_opcao;
    public int    $variacao_id;
    public string $valor;
    public function __construct(int $id=0,int $vid=0,string $v=''){
        $this->id_opcao    = $id;
        $this->variacao_id = $vid;
        $this->valor       = $v;
    }
    public function getId(): int         { return $this->id_opcao; }
    public function getVariacaoId(): int { return $this->variacao_id; }
    public function getValor(): string   { return $this->valor; }
}
