<?php
namespace Plum\Inject\Impl\Binder;

use Plum\Inject\Binding;
use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\KeyFactory;
use Plum\Reflect\Parameter;
use Plum\Reflect\Type;

class JustInTimeBinder
{
    private $bindings;

    public function __construct(Bindings $bindings)
    {
        $this->bindings = $bindings;
    }

    /**
     * Binds given type
     *
     * @param Type $type
     *
     * @return JustInTimeBinding
     *
     * @throws ConfigurationException if the type is abstract or not
     *      instantiable
     */
    public function bind(Type $type)
    {
        if ($type->isAbstract()) throw new ConfigurationException(
            "Missing binding for abstract type {$type->name}"
        );

        if (!$type->isInstantiable()) throw new ConfigurationException(
            "Provider binding is required for {$type->name}, since it is not ".
            "instantiable"
        );

        $c = $type->getConstructor();
        if (!$c) {
            $b = new JustInTimeBinding($type->name, []);
        } else {
            $dependencies = [];
            foreach ($c->getParameters() as $p) {
                $dependencies[] = $this->bindDependency($p);
            }

            $b = new JustInTimeBinding($type->name, $dependencies);
        }

        $this->bindings->put($b);

        return $b;
    }

    /**
     * Binds given parameter dependency
     *
     * @param Parameter $param
     *
     * @return Binding
     *
     * @throws ConfigurationException if binding can't be found
     */
    public function bindDependency(Parameter $param)
    {
        $k = KeyFactory::keyOfDependency($param);
        $b = $this->bindings->get($k);
        if ($b)
            return $b;

        $c = $param->getClass();

        return $this->bind($c);
    }
} 
