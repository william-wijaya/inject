<?php
namespace Plum\Inject\Binding;

use Plum\Inject\Binding;
use Plum\Inject\Key;

class JustInTimeBinding extends Binding
{
    private $dependencies;

    public function __construct($type, array $dependencies)
    {
        parent::__construct(Key::ofType($type));

        $this->dependencies = $dependencies;
    }

    /**
     * Returns the class name
     *
     * @return string
     */
    public function type()
    {
        return $this->key()->type();
    }

    /**
     * Returns the constructor dependencies
     *
     * @return Binding[]
     */
    public function dependencies()
    {
        return $this->dependencies;
    }
} 
