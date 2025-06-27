<?php
class Variacao
{
    public int    $id_variacao;
    public string $nome;
    public function __construct(int $id=0,string $n=''){
        $this->id_variacao=$id;
        $this->nome       =$n;
    }
    public function getId(): int     { return $this->id_variacao; }
    public function getNome(): string{ return $this->nome; }
}
