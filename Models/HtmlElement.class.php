<?php
class HtmlElement implements Componente
{
    public function __construct(
        private string $tag,
        private string $conteudo = '',
        private array  $attrs   = []
    ) {}

    public function criar(): string
    {
        $a = '';
        foreach ($this->attrs as $k=>$v) {
            $a .= " {$k}=\"{$v}\"";
        }
        return "<{$this->tag}{$a}>{$this->conteudo}</{$this->tag}>";
    }
}
