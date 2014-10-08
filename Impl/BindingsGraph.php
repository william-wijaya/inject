<?php
namespace Plum\Inject\Impl;

use Plum\Inject\Binding;
use Plum\Inject\BindingVisitor;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\Binder\JustInTimeBinder;
use Plum\Inject\Impl\Binder\ModuleBinder;
use Plum\Inject\Key;
use Plum\Reflect\Reflection;

interface BindingsGraph
{
    /**
     * Returns binding of given key, if no binding is found then attempts
     * to create it
     *
     * @param Key $key
     *
     * @return Binding
     *
     * @throws ConfigurationException if no binding is found and unable to
     *      create it
     */
    public function get(Key $key);

    /**
     * Visits all bindings in the graph
     *
     * @param BindingVisitor $visitor
     */
    public function traverse(BindingVisitor $visitor);
}

