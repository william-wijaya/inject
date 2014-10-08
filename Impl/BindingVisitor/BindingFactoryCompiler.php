<?php
namespace Plum\Inject\Impl\BindingVisitor;

use Plum\Gen\CodeWriter;
use Plum\Inject\Binding;
use Plum\Inject\Binding\ArrayBinding;
use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Impl\Binding\InstanceProviderBinding;
use Plum\Inject\Key;
use Plum\Inject\Named;
use Plum\Inject\Qualifier;

class BindingFactoryCompiler extends AbstractBindingVisitor
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
        $w->write("new ArrayBinding(");
        $this->writeKey($array->key());
        $w->write(", [");
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
        $w->write("])");
    }

    /**
     * {@inheritdoc}
     */
    public function visitScoped(ScopedBinding $scoped)
    {
        $w = $this->writer;
        $w->write("new ScopedBinding(");
            $w->write("new ", get_class($scoped->scope()), ", ");
            $scoped->delegate()->accept($this);
        $w->write(")");
    }

    /**
     * {@inheritdoc}
     */
    public function visitProvider(ProviderBinding $provider)
    {
        $w = $this->writer;
        $k = $provider->key();
        $m = $provider->method();

        $w->write("new ", get_class($provider), "(");
        $w->indent();
            $this->writeKey($k);
            $w->writeln(", ");
            $w->write('$r->ofType(', $m->getType()->name, "::class)");
            $w->write("->getMethod(");
            $w->literal($m->name);
            $w->writeln("), ");
            $this->writeDependencies($provider->dependencies());
            $w->writeln(", ", $provider->index());
        $w->outdent();
        $w->write(")");
    }

    /**
     * {@inheritdoc}
     */
    public function visitJustInTime(JustInTimeBinding $jit)
    {
        $w = $this->writer;
        $k = $jit->key();

        $w->write("new JustInTimeBinding(", $k->type(), "::class");
        $w->write(", ");
        $this->writeDependencies($jit->dependencies());
        $w->write(")");
    }

    /**
     * Writes key construction code
     *
     * @param Key $key
     */
    public function writeKey(Key $key)
    {
        $w = $this->writer;
        $q = $key->qualifier();

        if ($key->isArray())
            $w->write("Key::ofArray(");
        else if ($key->isConstant())
            $w->write("Key::ofConstant(");
        else
            $w->write("Key::ofType(", $key->type(), "::class, ");

        if ($q === null)
            $w->write("null");
        else
            $this->writeQualifier($q);

        $w->write(")");
    }

    /**
     * Writes qualifier
     *
     * @param Qualifier $q
     */
    public function writeQualifier(Qualifier $q)
    {
        $w = $this->writer;
        if ($q instanceof Named) {
            $w->write("Named::name(");
            $w->literal($q->value);
            $w->write(")");
        } else {
            $w->write("unserialize(");
            $w->literal(serialize($q));
            $w->write(")");
        }
    }

    /**
     * Writes dependencies
     *
     * @param Binding[] $dependencies
     */
    public function writeDependencies(array $dependencies)
    {
        $w = $this->writer;
        $w->write("[");
        if ($dependencies) {
            $w->indent();
            foreach ($dependencies as $d) {
                $d->accept($this);
                $w->writeln(",");
            }
            $w->outdent();
        }
        $w->write("]");
    }
}
