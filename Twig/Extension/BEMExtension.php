<?php
namespace Kna\BEMBundle\Twig\Extension;


use Kna\BEMBundle\BEM\Block;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

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
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction($this->blockFunctionName, [$this, 'b'], ['is_safe' => ['all'] ]),
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