<?php

namespace Kna\BEMBundle\Tests\BEM;


use Kna\BEMBundle\BEM\Block;
use Kna\BEMBundle\BEM\Element;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
    /**
     * @var Block|MockObject
     */
    protected $block = null;

    protected function createBlock(string $name): Block
    {
        $block =  $this
            ->getMockBuilder(Block::class)
            ->setConstructorArgs([$name])
            ->setMethods(null)
            ->getMock()
        ;

        return $block;
    }

    protected function setUp(): void
    {
        $this->block = $this->createBlock('block');
    }

    public function testCreateElementMethodReturnsValidElementInstance(): void
    {
        $elem = $this->block->createElement('elem');

        $this->assertInstanceOf(Element::class, $elem);
        $this->assertEquals('block__elem', $elem->buildClasses());
    }

    public function testEMethodIsAliasForCreateElement(): void
    {
        $block = $this
            ->getMockBuilder(Block::class)
            ->setConstructorArgs(['block'])
            ->setMethods(['createElement'])
            ->getMock()
        ;

        $block->expects($this->once())->method('createElement');
        $block->e('elem');
    }

    public function testResolveBaseClassMethodReturnsName(): void
    {
        $this->assertEquals('block', $this->block->resolveBaseClass());
    }
}