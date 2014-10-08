<?php
namespace Plum\Inject\Tests\Injector;

use Plum\Inject\Injector;
use Plum\Inject\Scope;
use Plum\Inject\Singleton;

class ScopeFixture extends Scope
{

}

class InjectorScopingTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_create_child_injector()
    {
        $this->assertInstanceOf(
            Injector::class,
            Injector::create()->fork(new ScopeFixture())
        );
    }

    /** @test */
    function it_should_returns_different_injector()
    {
        $parent = Injector::create();
        $child = $parent->fork(new ScopeFixture());

        $this->assertNotEquals($parent, $child);
    }
} 
