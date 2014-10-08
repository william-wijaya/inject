<?php
namespace Plum\Inject\Impl\Graph;

use Plum\Inject\Binding;
use Plum\Inject\BindingVisitor;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\Binder\JustInTimeBinder;
use Plum\Inject\Impl\Binder\ModuleBinder;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\BindingsGraph;
use Plum\Inject\Impl\BindingVisitor\FutureBindingResolver;
use Plum\Inject\Impl\Scoping;
use Plum\Inject\Key;
use Plum\Reflect\Reflection;

class BindingsGraphImpl implements BindingsGraph
{
    private $jit;
    private $bindings;
    private $reflection;

    protected function __construct(
        Bindings $bindings, Reflection $reflection, JustInTimeBinder $jit
    )
    {
        $this->jit = $jit;
        $this->bindings = $bindings;
        $this->reflection = $reflection;
    }

    /**
     * {@inheritdoc}
     */
    public function get(Key $key)
    {
        $b = $this->bindings->get($key);
        if ($b)
            return $b;

        if (!$key->isType()) throw new ConfigurationException(
            "Unable to find or create binding of key {$key}"
        );
        if ($key->qualifier()) throw new ConfigurationException(
            "No configured binding for key {$key}"
        );

        $t = $this->reflection->ofType($key->type());

        return $this->jit->bind($t);
    }

    /**
     * {@inheritdoc}
     */
    public function traverse(BindingVisitor $visitor)
    {
        foreach ($this->bindings as $b)
            $b->accept($visitor);
    }

    /**
     * Creates a bindings graph
     *
     * @param Bindings $bindings
     * @param Reflection $reflection
     * @param Scoping $scoping
     *
     * @return BindingsGraph
     */
    public static function create(
        Bindings $bindings, Reflection $reflection, Scoping $scoping
    )
    {
        $mb = new ModuleBinder($bindings, $reflection);
        foreach ($scoping->modules() as $i => $m)
            $mb->bind($m, $i);

        $jit = new JustInTimeBinder($bindings);

        $g = new BindingsGraphImpl($bindings, $reflection, $jit);
        $g->traverse(new FutureBindingResolver($g));

        return $g;
    }
}

