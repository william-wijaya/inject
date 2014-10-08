<?php
namespace Plum\Inject\Binding;

use Plum\Inject\Binding;
use Plum\Inject\Key;

class ArrayBinding extends Binding
{
    private $elements;

    /**
     * @param Key $key
     * @param Binding[] $elements
     */
    public function __construct(Key $key, array $elements)
    {
        parent::__construct($key);

        $this->elements = $elements;
    }

    public function add(ProviderBinding $element)
    {
        $this->elements[] = $element;
    }

    /**
     * Returns the array elements
     *
     * @return Binding[]
     */
    public function elements()
    {
        return $this->elements;
    }
} 
