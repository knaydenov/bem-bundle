<?php

namespace Kna\BEMBundle\Tests\BEM;

use Kna\BEMBundle\BEM\Block;
use Kna\BEMBundle\BEM\Element;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    /**
     * @var Element|MockObject
     */
    protected $element = null;

    /**
     * @var null Block\MockObject
     */
    protected $block = null;

    protected function createBlock(string $name): Block
    {
        $block = $this
            ->getMockBuilder(Block::class)
            ->setConstructorArgs(['block'])
            ->setMethods(null)
            ->getMock();
        return $block;
    }

    protected function createElement(Block $block, string $name): Element
    {
        $elem =  $this
            ->getMockBuilder(Element::class)
            ->setConstructorArgs([$name, $block])
            ->setMethods(null)
            ->getMock()
        ;

        return $elem;
    }

    protected function setUp(): void
    {
        $this->block = $this->createBlock('block');
        $this->element = $this->createElement($this->block, 'elem');
    }

    public function testParentBlockIsSet(): void
    {
        $this->assertEquals($this->block, $this->element->getBlock());
    }

    public function testResolveBaseClassMethodReturnsValidString(): void
    {
        $this->assertEquals('block__elem', $this->element->resolveBaseClass());
    }
}