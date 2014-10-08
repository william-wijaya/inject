<?php
namespace Plum\Inject\Impl\BindingVisitor;

use Plum\Inject\Binding;
use Plum\Inject\Binding\ArrayBinding;
use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Impl\Binding\FutureBinding;
use Plum\Inject\Impl\BindingsGraph;

class FutureBindingResolver extends AbstractBindingVisitor
{
    private $graph;

    public function __construct(BindingsGraph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * {@inheritdoc}
     */
    public function visit(Binding $b)
    {
        if ($b instanceof FutureBinding)
            $this->visitFuture($b);
        else
            parent::visit($b);
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray(ArrayBinding $array)
    {
        foreach ($array->elements() as $e)
            $e->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitScoped(ScopedBinding $scoped)
    {
        $scoped->delegate()->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitProvider(ProviderBinding $provider)
    {
        foreach ($provider->dependencies() as $d)
            $d->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitJustInTime(JustInTimeBinding $jit)
    {
        foreach ($jit->dependencies() as $d)
            $d->accept($this);
    }

    /**
     * {@inheritdoc}
     */
    public function visitFuture(FutureBinding $future)
    {
        $k = $future->key();
        $b = $this->graph->get($k);
        if ($b)
            $future->resolveWith($b);
    }
}
