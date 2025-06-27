<?php
class Composite implements Componente
{
    private array $itens = [];

    public function add(Componente $c): void
    {
        $this->itens[] = $c;
    }

    public function criar(): string
    {
        $out = '';
        foreach ($this->itens as $c) {
            $out .= $c->criar();
        }
        return $out;
    }
}
