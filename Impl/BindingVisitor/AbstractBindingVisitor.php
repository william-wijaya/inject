<?php
namespace Plum\Inject\Impl\BindingVisitor;

use Plum\Inject\Binding;
use Plum\Inject\BindingVisitor;
use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Binding\ArrayBinding;
use Plum\Inject\Impl\Binding\FutureBinding;

abstract class AbstractBindingVisitor implements BindingVisitor
{

    /**
     * {@inheritdoc}
     */
    public function visit(Binding $b)
    {
        if ($b instanceof ArrayBinding)
            $this->visitArray($b);
        else if ($b instanceof ScopedBinding)
            $this->visitScoped($b);
        else if ($b instanceof ProviderBinding)
            $this->visitProvider($b);
        else if ($b instanceof JustInTimeBinding)
            $this->visitJustInTime($b);
    }

    public abstract function visitArray(ArrayBinding $array);

    public abstract function visitScoped(ScopedBinding $scoped);

    public abstract function visitProvider(ProviderBinding $provider);

    public abstract function visitJustInTime(JustInTimeBinding $jit);
}
