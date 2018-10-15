<?php

namespace Kna\BEMBundle\Tests\BEM;

use Kna\BEMBundle\BEM\Node;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    /**
     * @var Node|MockObject
     */
    protected $node = null;

    protected function createNode(string $name): Node
    {
        $node =  $this
            ->getMockBuilder(Node::class)
            ->setConstructorArgs([$name])
            ->setMethods(['resolveBaseClass'])
            ->getMockForAbstractClass()
        ;

        $node->method('resolveBaseClass')->willReturn($name);

        return $node;
    }

    public function setUp()
    {
        $this->node = $this->createNode('block');
    }

    public function testNameIsSet(): void
    {
        $this->assertEquals('block', $this->node->getName());
    }

    public function testSetModifierDefaultValue(): void
    {
        $this->node->addModifier('modifier');
        $this->assertEquals(true, $this->node->getModifier('modifier'));
    }

    public function testSetModifierBooleanValue(): void
    {
        $this->node->addModifier('modifier', true);
        $this->assertEquals(true, $this->node->getModifier('modifier'));

        $this->node->addModifier('modifier', false);
        $this->assertEquals(false, $this->node->getModifier('modifier'));
    }

    public function testSetModifierStringValue(): void
    {
        $this->node->addModifier('modifier', 'yes');
        $this->assertEquals('yes', $this->node->getModifier('modifier'));
    }

    public function testSetModifierIntegerValue(): void
    {
        $this->node->addModifier('modifier', 0);
        $this->assertEquals(0, $this->node->getModifier('modifier'));

        $this->node->addModifier('modifier', 1);
        $this->assertEquals(1, $this->node->getModifier('modifier'));
    }

    public function testRemoveModifier(): void
    {
        $this->node->addModifier('modifier', 0);
        $this->assertTrue($this->node->hasModifier('modifier'));

        $this->node->removeModifier('modifier');
        $this->assertFalse($this->node->hasModifier('modifier'));
    }

    public function testGetNonexistentModifierIsNull(): void
    {
        $this->assertNull($this->node->getModifier('modifier'));
    }

    public function testHasExistentModifierIsTrue(): void
    {
        $this->node->addModifier('modifier');
        $this->assertTrue($this->node->hasModifier('modifier'));
    }

    public function testHasNonexistentModifierIsFalse(): void
    {
        $this->assertFalse($this->node->hasModifier('modifier'));
    }

    public function testGetModifiersReturnsValidArray(): void
    {
        $this->node->addModifier('mod1');
        $this->node->addModifier('mod2', true);
        $this->node->addModifier('mod3', false);
        $this->node->addModifier('mod4', 'yes');
        $this->node->addModifier('mod5', 0);
        $this->node->addModifier('mod6', 1);
        $this->node->addModifier('mod7', 'remove-it');
        $this->node->removeModifier('mod7');
        $this->node->addModifier('mod8', 'overwrite-it');
        $this->node->addModifier('mod8', 'overwritten');

        $expected = [
            'mod1' => true,
            'mod2' => true,
            'mod3' => false,
            'mod4' => 'yes',
            'mod5' => 0,
            'mod6' => 1,
            'mod8' => 'overwritten',
        ];

        $this->assertEquals($expected, $this->node->getModifiers());
    }

    public function testMixIsAdded(): void
    {
        $block1 = $this->createNode('block1');
        $this->node->addMix($block1);

        $this->assertTrue($this->node->hasMix($block1));
    }

    public function testMixIsRemoved(): void
    {
        $block1 = $this->createNode('block1');
        $this->node->addMix($block1);

        $this->assertTrue($this->node->hasMix($block1));

        $this->node->removeMix($block1);

        $this->assertFalse($this->node->hasMix($block1));
    }

    public function testHasMixMethodReturnsValidValues(): void
    {
        $block1 = $this->createNode('block1');
        $block2 = $this->createNode('block2');
        $block3 = $this->createNode('block3');

        $this->node->addMix($block1);
        $this->node->addMix($block2);

        $this->assertTrue($this->node->hasMix($block1));
        $this->assertTrue($this->node->hasMix($block2));
        $this->assertFalse($this->node->hasMix($block3));

        $this->node->removeMix($block1);

        $this->assertFalse($this->node->hasMix($block1));
        $this->assertTrue($this->node->hasMix($block2));
        $this->assertFalse($this->node->hasMix($block3));
    }

    public function testGetMixMethodReturnsValidArray(): void
    {
        $block1 = $this->createNode('block1');
        $block2 = $this->createNode('block2');
        $block3 = $this->createNode('block3');
        $block4 = $this->createNode('block4');

        $this->node->addMix($block1);
        $this->node->addMix($block2);
        $this->node->addMix($block4);
        $this->node->removeMix($block1);

        $expected = [
            $block2,
            $block4
        ];

        $this->assertEquals($expected, $this->node->getMixes());
    }

    public function testAddClassIsValid(): void
    {
        $this->node->addClass('class');

        $this->assertTrue($this->node->hasClass('class'));
    }

    public function testSetClassesMethodSetsValidClasses(): void
    {
        $this->node->setClasses(['class1', 'class2', 'class4']);

        $expected = ['class1', 'class2', 'class4'];

        $this->assertEquals($expected, $this->node->getClasses());
    }

    public function testClassIsRemoved(): void  {
        $this->node->addClass('class');

        $this->assertTrue($this->node->hasClass('class'));

        $this->node->removeClass('class');

        $this->assertFalse($this->node->hasClass('class'));
    }

    public function testMMethodIsAliasForAddModifier(): void
    {
        $node = $this
            ->getMockBuilder(Node::class)
            ->setConstructorArgs(['block'])
            ->setMethods(['addModifier'])
            ->getMockForAbstractClass()
        ;

        $node->expects($this->once())->method('addModifier');
        $node->m('modifier');
    }

    public function testBuildClassesMethodReturnsImplodedClasses(): void
    {
        $node = $this
            ->getMockBuilder(Node::class)
            ->setConstructorArgs(['block'])
            ->setMethods(['resolveClasses'])
            ->getMockForAbstractClass()
        ;

        $node->expects($this->once())->method('resolveClasses')->willReturn(['classA', 'classB', 'classC']);
        $this->assertEquals('classA classB classC', $node->buildClasses());
    }

    public function testResolveModifiersClassesMethodReturnsValidClasses(): void
    {
        $this->node->addModifier('boolean');
        $this->node->addModifier('string', 'yes');
        $this->node->addModifier('integer', 5);

        $expected = [
            'block--boolean',
            'block--string_yes',
            'block--integer_5'
        ];

        $this->assertEquals($expected, $this->node->resolveModifiersClasses());
    }

    public function testResolveMixesClassesMethodReturnsValidClasses(): void
    {
        $block1 = $this->createNode('block1');
        $block1->addModifier('boolean');
        $block2 = $this->createNode('block2');
        $block2->addModifier('string', 'yes');

        $this->node->addMix($block1);
        $this->node->addMix($block2);

        $expected = [
            'block1',
            'block1--boolean',
            'block2',
            'block2--string_yes'
        ];

        $this->assertEquals($expected, $this->node->resolveMixesClasses());
    }

    public function testResloveClassesMethodReturnsValidClasses(): void
    {
        $block1 = $this->createNode('block1');
        $block1->addModifier('boolean');
        $block2 = $this->createNode('block2');
        $block2->addModifier('string', 'yes');

        $this->node->addMix($block1);
        $this->node->addMix($block2);

        $this->node->addModifier('modA');
        $this->node->addModifier('modB', 'yes');

        $this->node->addClass('super');
        $this->node->addClass('good');
        $this->node->addClass('block1'); // should contain only unique values

        $expected = [
            'block',
            'block--modA',
            'block--modB_yes',
            'block1',
            'block1--boolean',
            'block2',
            'block2--string_yes',
            'super',
            'good'
        ];

        $this->assertEquals($expected, $this->node->resolveClasses());
    }

    public function testClassesMethodIsAliasForAddModifier(): void
    {
        $node = $this
            ->getMockBuilder(Node::class)
            ->setConstructorArgs(['block'])
            ->setMethods(['resolveClasses'])
            ->getMockForAbstractClass()
        ;

        $node->expects($this->once())->method('resolveClasses')->willReturn(['classA', 'classB']);

        $expected = [
            'classA',
            'classB'
        ];

        $this->assertEquals($expected, $node->classes());
    }
}