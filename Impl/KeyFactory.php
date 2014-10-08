<?php
namespace Plum\Inject\Impl;

use Plum\Inject\ConfigurationException;
use Plum\Inject\Key;
use Plum\Inject\Provides;
use Plum\Inject\Qualifier;
use Plum\Reflect\Method;
use Plum\Reflect\Parameter;

class KeyFactory
{
    /**
     * Returns the key of given provider method
     *
     * @param Method $method
     *
     * @return Key
     *
     * @throws ConfigurationException
     */
    public static function keyOfProvider(Method $method)
    {
        $p = $method->getAnnotation(Provides::class);
        $q = $method->getAnnotation(Qualifier::class);

        if (!$q &&
            (
                $p->value === Provides::CONSTANT
                ||
                $p->value === Provides::ELEMENT
            )
        ) throw new ConfigurationException(
            "Qualifier is required for constant and array element provider ".
            "method, {$method->class}::{$method->name}()"
        );

        if ($p->value === Provides::CONSTANT)
            return Key::ofConstant($q);

        if ($p->value === Provides::ELEMENT)
            return Key::ofArray($q);

        return Key::ofType($p->value, $q);
    }

    /**
     * Returns the key of given dependency parameter
     *
     * @param Parameter $param
     *
     * @return Key
     *
     * @throws ConfigurationException
     */
    public static function keyOfDependency(Parameter $param)
    {
        $q = $param->getAnnotation(Qualifier::class);
        $c = $param->getClass();
        if ($c)
            return Key::ofType($c->name, $q);

        if (!$q) throw new ConfigurationException(
            "Qualifier annotation is required for parameter ".
            $param->getDeclaringClass()->name."::".$param->getDeclaringFunction()->name.
            "(\${$param->name})"
        );

        if ($param->isArray())
            return Key::ofArray($q);

        return Key::ofConstant($q);
    }
} 
