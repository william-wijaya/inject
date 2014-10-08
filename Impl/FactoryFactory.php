<?php
namespace Plum\Inject\Impl;

use Plum\Gen\ClassFileWriter;
use Plum\Gen\CodeSpace;
use Plum\Gen\CodeWriter;
use Plum\Inject\Binding;
use Plum\Inject\Impl\BindingVisitor\BindingFactoryCompiler;
use Plum\Inject\Impl\BindingVisitor\InstanceFactoryCompiler;
use Plum\Inject\Key;
use Plum\Inject\Named;
use Plum\Reflect\Method;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\Binding\JustInTimeBinding;

class FactoryFactory
{
    private $space;
    private $graph;
    private $scoping;

    public function __construct(
        CodeSpace $space, BindingsGraph $graph, Scoping $scoping
    )
    {
        $this->space = $space;
        $this->graph = $graph;
        $this->scoping = $scoping;
    }

    /**
     * Returns factory class of given key
     *
     * @param Key $key
     *
     * @return string the factory class name
     */
    public function get(Key $key)
    {
        $factoryClass =
            "InjectFactory__".$this->scoping->hashCode().
            "__".$key->hashCode();

        if ($this->space->load($factoryClass))
            return $factoryClass;

        $binding = $this->graph->get($key);

        $w = new CodeWriter();
        $cls = new ClassFileWriter($w);
        $cls->useType(Key::class)
            ->useType(Named::class)
            ->useType(ScopedBinding::class)
            ->useType(ProviderBinding::class)
            ->useType(JustInTimeBinding::class)
            ->beginClass(null, $factoryClass)
                ->method(Method::IS_STATIC | Method::IS_FINAL, "getBinding")
                ->parameter("r")
                ->body(function(CodeWriter $w) use ($binding) {
                    $w->write("return ");
                    $binding->accept(
                        new BindingFactoryCompiler($w)
                    );
                    $w->write(";");
                })
                ->method(Method::IS_STATIC | Method::IS_FINAL, "getInstance")
                ->parameter("i")->parameter("s")
                ->body(function(CodeWriter $w) use ($binding) {
                    $w->writeln('$m = $s->modules();');
                    $w->nl();
                    $w->write("return ");
                    $binding->accept(
                        new InstanceFactoryCompiler($w)
                    );
                    $w->write(";");
                })
            ->endClass();

        $this->space->save($factoryClass, $w);
        $this->space->load($factoryClass);

        return $factoryClass;
    }

    /**
     *
     *
     * @param Key $key
     *
     * @return callable
     */
    public function getInstanceFactory(Key $key)
    {
        return [$this->get($key), "getInstance"];
    }

    /**
     * @param Key $key
     *
     * @return callable
     */
    public function getBindingFactory(Key $key)
    {
        return [$this->get($key), "getBinding"];
    }
} 
