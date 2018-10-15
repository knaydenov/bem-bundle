<?php
namespace Kna\BEMBundle\BEM;


abstract class Node
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var array|string[]
     */
    protected $classes = [];
    /**
     * @var array
     */
    protected $modifiers = [];

    /**
     * @var array|Node[]
     */
    private $mixes = [];

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    private function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function addModifier(string $name, $value = true): Node
    {
        $this->modifiers[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeModifier(string $name): Node
    {
        unset($this->modifiers[$name]);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getModifier(string $name)
    {
        return $this->hasModifier($name) ? $this->modifiers[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasModifier(string $name): bool
    {
        return array_key_exists($name, $this->modifiers);
    }

    /**
     * @return array
     */
    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function addMix(Node $node): Node
    {
        if (!$this->hasMix($node)) {
            $this->mixes[] = $node;
        }
        return $this;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function removeMix(Node $node): Node
    {
        if (false !== $index = array_search($node, $this->mixes, true)) {
            unset($this->mixes[$index]);
        }
        $this->mixes = array_values($this->mixes);
        return $this;
    }

    /**
     * @param Node $node
     * @return bool
     */
    public function hasMix(Node $node): bool
    {
        return in_array($node, $this->mixes, true);
    }

    /**
     * @return array|Node[]
     */
    public function getMixes(): array
    {
        return $this->mixes;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addClass(string $name): Node
    {
        if (!$this->hasClass($name)) {
            $this->classes[] = $name;
        }
        return $this;
    }

    /**
     * @param array $classes
     * @return $this
     */
    public function setClasses(array $classes): Node
    {
        $classes = array_filter($classes, function ($class) {
            return is_string($class);
        });
        $this->classes = array_unique($classes);
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function removeClass(string $name): Node{
        if (false !== $index = array_search($name, $this->classes)) {
            unset($this->classes[$index]);
        }
        $this->classes = array_values($this->classes);
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasClass(string $name): bool
    {
        return in_array($name, $this->classes);
    }

    /**
     * @return array|string[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function m(string $name, $value = true): Node
    {
        return $this->addModifier($name, $value);
    }

    public function mix(Node $node): Node
    {
        return $this->addMix($node);
    }

    /**
     * @return string
     */
    public function buildClasses(): string
    {
        return implode(' ', $this->resolveClasses());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->buildClasses();
    }

    public function resolveModifiersClasses(): array
    {
        $classes = [];

        foreach($this->modifiers as $modifier => $value) {
            switch(true) {
                case is_bool($value):
                    if($value == true) {
                        $classes[] = sprintf('%s--%s', $this->resolveBaseClass(), $modifier);
                    }
                    break;
                case is_null($value):
                    break;
                case is_string($value):
                default:
                    $classes[] = sprintf('%s--%s_%s', $this->resolveBaseClass(), $modifier, $value);
                    break;

            }
        }

        return $classes;
    }

    public function resolveMixesClasses(): array {
        $classes = [];

        foreach ($this->mixes as $mix) {
            $classes = array_merge($classes, $mix->resolveClasses());
        }

        return array_unique($classes);
    }

    /**
     * @return array|string[]
     */
    public function resolveClasses(): array
    {
        $classes = [$this->resolveBaseClass()];

        $classes = array_merge($classes, $this->resolveModifiersClasses());

        $classes = array_merge($classes, $this->resolveMixesClasses());

        $classes = array_merge($classes, $this->classes);

        return array_unique($classes);
    }

    /**
     * @return array
     */
    public function classes(): array
    {
        return $this->resolveClasses();
    }

    /**
     * @return string
     */
    abstract public function resolveBaseClass(): string;
}