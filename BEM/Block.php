<?php
namespace Kna\BEMBundle\BEM;


class Block extends Node
{
    /**
     * @param string $name
     * @return Element
     */
    public function createElement(string $name): Element
    {
        return new Element($name, $this);
    }

    /**
     * @param string $name
     * @return Element
     */
    public function e(string $name): Element
    {
        return $this->createElement($name);
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBaseClass(): string
    {
        return $this->getName();
    }
}