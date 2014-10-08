<?php
namespace Plum\Inject\Impl\Graph;

use Plum\Inject\BindingVisitor;
use Plum\Inject\Key;
use Plum\Inject\Binding;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\BindingsGraph;
use Plum\Inject\Impl\Scoping;
use Plum\Reflect\Reflection;

class LazyGraph implements BindingsGraph
{
    /**
     * @var BindingsGraph
     */
    private $delegate;

    private $bindings;

    private $reflection;

    private $scoping;

    public function __construct(
        Bindings $bindings, Reflection $reflection, Scoping $scoping
    )
    {
        $this->bindings = $bindings;
        $this->reflection = $reflection;
        $this->scoping = $scoping;
    }

    public function delegate()
    {
        return $this->delegate
            ?: $this->delegate = BindingsGraphImpl::create(
                $this->bindings, $this->reflection, $this->scoping
            );
    }

    /**
     * {@inheritdoc}
     */
    public function get(Key $key)
    {
        return $this->delegate()->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function traverse(BindingVisitor $visitor)
    {
        return $this->delegate()->traverse($visitor);
    }
}
