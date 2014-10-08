<?php
namespace Plum\Inject;

abstract class Binding
{
    private $key;

    public function __construct(Key $key)
    {
        $this->key = $key;
    }

    /**
     * Returns the key
     *
     * @return Key
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Accepts a binding visitor
     *
     * @param BindingVisitor $visitor
     */
    public function accept(BindingVisitor $visitor)
    {
        $visitor->visit($this);
    }
} 
