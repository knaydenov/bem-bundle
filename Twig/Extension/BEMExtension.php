<?php
namespace Kna\BEMBundle\Twig\Extension;


use Kna\BEMBundle\BEM\Block;
use Twig\Extension\AbstractExtension;

class BEMExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private $blockFunctionName = 'b';

    public function setBlockFunctionName(string $name): void
    {
        $this->blockFunctionName = $name;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction($this->blockFunctionName, [$this, 'b'], ['is_safe' => ['all'] ]),
        ];
    }

    /**
     * @param $name
     * @return Block
     */
    public function b($name) {
        return new Block($name);
    }
}