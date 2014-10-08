<?php
namespace Plum\Inject\Binding;

use Plum\Inject\Key;
use Plum\Inject\Binding;
use Plum\Reflect\Method;

class ProviderBinding extends Binding
{
    private $index;
    private $method;
    private $dependencies;

    /**
     * @param Key $key
     * @param Method $method
     * @param Binding[] $dependencies
     * @param int $index
     */
    public function __construct(
        Key $key, Method $method, array $dependencies, $index
    )
    {
        parent::__construct($key);

        $this->index = $index;
        $this->method = $method;
        $this->dependencies = $dependencies;
    }

    /**
     * Returns the module index
     *
     * @return int
     */
    public function index()
    {
        return $this->index;
    }

    /**
     * Returns the provider binding method
     *
     * @return Method
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Returns the dependencies
     *
     * @return Binding[]
     */
    public function dependencies()
    {
        return $this->dependencies;
    }
} 
