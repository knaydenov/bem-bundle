<?php
namespace Kna\BEMBundle\BEM;


class Element extends Node
{
    /**
     * @var Block
     */
    protected $block;

    public function __construct(string $name, Block $block)
    {
        $this->setBlock($block);
        parent::__construct($name);
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return $this->block;
    }

    /**
     * @param Block $block
     */
    private function setBlock(Block $block): void
    {
        $this->block = $block;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveBaseClass(): string
    {
        return sprintf('%s__%s', $this->getBlock()->getName(), $this->getName());
    }
}