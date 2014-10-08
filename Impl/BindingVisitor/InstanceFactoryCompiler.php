<?php
namespace Plum\Inject\Impl\BindingVisitor;

use Plum\Gen\CodeWriter;
use Plum\Inject\Binding;
use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Impl\Binding\InjectorBinding;
use Plum\Inject\Impl\Binding\InstanceProviderBinding;
use Plum\Inject\Impl\Binding\StaticProviderBinding;
use Plum\Inject\Binding\ArrayBinding;

class InstanceFactoryCompiler extends AbstractBindingVisitor
{
    private $writer;

    public function __construct(CodeWriter $writer)
    {
        $this->writer = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray(ArrayBinding $array)
    {
        $w = $this->writer;
        $w->write("[");

        $elements = $array->elements();
        if (count($elements) === 1) {
            reset($elements)->accept($this);
        } else {
            $w->indent();
            foreach ($elements as $e) {
                $e->accept($this);

                $w->writeln(",");
            }
            $w->outdent();
        }

        $w->write("]");
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
        $w = $this->writer;
        $m = $provider->method();
        $t = $m->getType();

        if ($m->isStatic())
            $w->write($t->name, "::", $m->name);
        else
            $w->write('$m[', $provider->index(), "]->", $m->name);

        $w->write("(");
        $this->writeDependencies($provider->dependencies());
        $w->write(")");
    }

    /**
     * {@inheritdoc}
     */
    public function visitJustInTime(JustInTimeBinding $jit)
    {
        $w = $this->writer;
        if ($jit instanceof InjectorBinding)
            $w->write('$i');
        else {
            $w->write("new ", $jit->type(), "(");
            $this->writeDependencies($jit->dependencies());
            $w->write(")");
        }
    }

    /**
     * Writes dependency parameters
     *
     * @param Binding[] $dependencies
     */
    public function writeDependencies(array $dependencies)
    {
        if (!$dependencies) return;

        $this->writer->indent();
        array_shift($dependencies)->accept($this);
        foreach ($dependencies as $d) {
            $this->writer->writeln(",");
            $d->accept($this);
        }
        $this->writer->outdent();
    }
} 
