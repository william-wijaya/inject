<?php
namespace Plum\Inject\Impl;

use Plum\Gen\CodeSpace;
use Plum\Inject\Binding;
use Plum\Inject\Env;
use Plum\Inject\Impl\Graph\BindingsGraphImpl;
use Plum\Inject\Impl\Graph\LazyGraph;
use Plum\Inject\Injector;
use Plum\Inject\Key;
use Plum\Inject\Scope;
use Plum\Reflect\Reflection;

class InjectorImpl extends Injector
{
    private $env;
    private $space;
    private $graph;
    private $scoping;
    private $factory;
    private $reflection;

    protected function __construct(
        Env $env, CodeSpace $space, Scoping $scoping,
        BindingsGraph $graph, FactoryFactory $factory, Reflection $reflection
    )
    {
        $this->env = $env;
        $this->space = $space;
        $this->graph = $graph;
        $this->scoping = $scoping;
        $this->factory = $factory;
        $this->reflection = $reflection;
    }

    /**
     * {@inheritdoc}
     */
    public function getBinding(Key $key)
    {
        $f = $this->factory->getBindingFactory($key);

        return $f($this->reflection);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Key $key)
    {
        $f = $this->factory->getInstanceFactory($key);

        return $f($this, $this->scoping);
    }

    /**
     * {@inheritdoc}
     */
    public function fork(Scope $scope, ...$modules)
    {
        $b = new Bindings();
        $s = new Scoping($scope, $modules);
        if ($this->env === Env::production()) {
            $g = new LazyGraph($b, $this->reflection, $this->scoping);
        } else {
            $g = BindingsGraphImpl::create($b, $this->reflection, $this->scoping);
        }

        $f = new FactoryFactory($this->space, $g, $s);

        return new InjectorImpl($this->env, $this->space, $s, $g, $f, $this->reflection);
    }
}
