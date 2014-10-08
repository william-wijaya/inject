<?php
namespace Plum\Inject;

interface BindingVisitor
{
    /**
     * Visits a binding
     *
     * @param Binding $binding
     */
    public function visit(Binding $binding);
} 
