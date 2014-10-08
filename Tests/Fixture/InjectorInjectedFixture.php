<?php
namespace Plum\Inject\Tests\Fixture;

use Plum\Inject\Injector;

class InjectorInjectedFixture
{
    private $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function injector()
    {
        return $this->injector;
    }
} 
