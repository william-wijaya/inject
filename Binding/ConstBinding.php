<?php
namespace Plum\Inject\Binding;

use Plum\Inject\Binding;
use Plum\Inject\Key;

/**
 * Represents constant value injection
 */
class ConstBinding extends Binding
{
    private $value;

    public function __construct(Key $key, $value)
    {
        parent::__construct($key);

        $this->value = $value;
    }

    /**
     * Returns the constant value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
} 
