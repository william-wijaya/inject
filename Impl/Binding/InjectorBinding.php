<?php
namespace Plum\Inject\Impl\Binding;

use Plum\Inject\Binding\JustInTimeBinding;
use Plum\Inject\Injector;

class InjectorBinding extends JustInTimeBinding
{
    public function __construct()
    {
        parent::__construct(Injector::class, []);
    }
} 
