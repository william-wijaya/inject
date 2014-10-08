<?php
namespace Plum\Inject\Impl;

use Plum\Inject\Impl\Binding\InjectorBinding;
use Plum\Inject\Key;
use Plum\Inject\Binding;

/**
 * Represents a map of {@link Key} hash code to it's corresponding
 * {@link Binding} instance
 */
class Bindings implements \IteratorAggregate
{
    private $map = [];

    public function __construct()
    {
        $this->put(new InjectorBinding());
    }

    /**
     * Sets binding into the map
     *
     * @param Binding $binding
     */
    public function put(Binding $binding)
    {
        $k = $binding->key();
        $h = $k->hashCode();

        $this->map[$h] = $binding;
    }

    /**
     * Returns the binding of given key
     *
     * @param Key $key
     * @return Binding|null
     */
    public function get(Key $key)
    {
        $h = $key->hashCode();
        if (isset($this->map[$h]))
            return $this->map[$h];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator(array_values($this->map));
    }
} 
