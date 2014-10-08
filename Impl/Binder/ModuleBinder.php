<?php
namespace Plum\Inject\Impl\Binder;

use Plum\Inject\Binding;
use Plum\Inject\Binding\ConstBinding;
use Plum\Inject\Binding\ArrayBinding;
use Plum\Inject\Binding\ScopedBinding;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\Binding\FutureBinding;
use Plum\Inject\Impl\Binding\OptionalBinding;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\KeyFactory;
use Plum\Inject\Provides;
use Plum\Inject\Scope;
use Plum\Reflect\Method;
use Plum\Reflect\Parameter;
use Plum\Reflect\Reflection;
use Plum\Reflect\Type;


class ModuleBinder
{
    private $bindings;
    private $reflection;

    public function __construct(Bindings $bindings, Reflection $reflection)
    {
        $this->bindings = $bindings;
        $this->reflection = $reflection;
    }

    /**
     * Binds given module
     *
     * @param string|object $module
     * @param int $index
     */
    public function bind($module, $index)
    {
        if (is_object($module))
            $t = $this->reflection->ofType(get_class($module));
        else
            $t = $this->reflection->ofType($module);

        $methods = array_filter(
            $t->getMethods(Method::IS_PUBLIC),
            function(Method $m) {
                return $m->isAnnotatedWith(Provides::class);
            }
        );

        if (!is_object($module)) foreach ($methods as $m) {
            if (!$m->isStatic()) throw new ConfigurationException(
                "Provider method {$t->name}::{$m->name}() requires instance ".
                "of the module"
            );
        }

        if ($methods) foreach ($methods as $m) {
            $this->bindProvider($m, $index);
        } else throw new ConfigurationException(
            "No provider method found in module {$t->name}"
        );
    }

    /**
     * Binds provider method
     *
     * @param Method $method
     * @param int $index
     */
    public function bindProvider(Method $method, $index)
    {
        $k = KeyFactory::keyOfProvider($method);

        $dependencies = [];
        foreach ($method->getParameters() as $p) {
            $dependencies[] = $this->bindDependency($p);
        }

        $b = new ProviderBinding($k, $method, $dependencies, $index);

        $s = $method->getAnnotation(Scope::class);
        if ($s) {
            $b = new ScopedBinding($s, $b);
        }

        if ($k->isArray()) {
            $array = $this->bindings->get($k);
            if (!$array) {
                $array = new ArrayBinding($k, []);

                $this->bindings->put($array);
            }

            $array->add($b);
        } else {
            $this->bindings->put($b);
        }
    }

    /**
     * @param Parameter $param
     *
     * @return Binding
     */
    public function bindDependency(Parameter $param)
    {
        $k = KeyFactory::keyOfDependency($param);
        if (!$param->isDefaultValueAvailable())
            return new FutureBinding($k);

        $value = $param->getDefaultValue();
        $const = new ConstBinding($k, $value);

        return new OptionalBinding($k, $const);
    }
} 
