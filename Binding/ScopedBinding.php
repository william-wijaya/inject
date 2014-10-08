<?php
namespace Plum\Inject\Binding;

use Plum\Inject\Binding;
use Plum\Inject\Scope;

class ScopedBinding extends Binding
{
    private $scope;
    private $delegate;

    public function __construct(Scope $scope, Binding $delegate)
    {
        parent::__construct($delegate->key());

        $this->scope = $scope;
        $this->delegate = $delegate;
    }

    /**
     * Returns the scope
     *
     * @return Scope
     */
    public function scope()
    {
        return $this->scope;
    }

    /**
     * Returns the delegate binding
     *
     * @return Binding
     */
    public function delegate()
    {
        return $this->delegate;
    }
} 
