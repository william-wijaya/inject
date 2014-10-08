<?php
namespace Plum\Inject\Tests;

use Plum\Gen\CodeSpace;
use Plum\Inject\Binding;
use Plum\Inject\Env;
use Plum\Inject\Injector;
use Plum\Inject\Key;
use Plum\Inject\Named;
use Plum\Inject\Singleton;
use Plum\Inject\Tests\Fixture\ArrayInjectedFixture;
use Plum\Inject\Tests\Fixture\ConstantInjectedFixture;
use Plum\Inject\Tests\Fixture\Dependency;
use Plum\Inject\Tests\Fixture\InjectorInjectedFixture;
use Plum\Inject\Tests\Fixture\ModuleFixture;
use Plum\Inject\Tests\Fixture\ScopeFixture;
use Plum\Inject\Tests\Fixture\TypeWithDependency;
use Plum\Inject\Binding\ProviderBinding;
use Plum\Reflect\Reflection;

class InjectorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_creates_default_injector()
    {
        $this->assertEquals(
            Injector::create(),
            Injector::create(Env::development(), sys_get_temp_dir())
        );
    }

    /** @test */
    function it_should_returns_instance_with_no_dependency()
    {
        $i = Injector::create()->getInstance(\stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $i);
    }

    /** @test */
    function it_should_returns_instance_with_dependency()
    {
        $i = Injector::create()->getInstance(TypeWithDependency::class);

        $this->assertInstanceOf(TypeWithDependency::class, $i);
        $this->assertInstanceOf(Dependency::class, $i->dependency());
    }

    /** @test */
    function it_should_returns_binding()
    {
        $k = Key::ofType(\stdClass::class);
        $b = Injector::create()->getBinding($k);

        $this->assertInstanceOf(Binding::class, $b);
    }

    /** @test */
    function it_should_returns_provider_binding()
    {
        $b = Injector::create(null, null, ModuleFixture::class)
                ->getBinding(Key::ofType(\stdClass::class));

        $this->assertInstanceOf(ProviderBinding::class, $b);
    }

    /** @test */
    function it_should_returns_the_injector_itself()
    {
        $i = Injector::create();

        $this->assertSame($i, $i->getInstance(Injector::class));
    }

    /** @test */
    function it_should_inject_the_injector_itself()
    {
        $i = Injector::create();
        $o = $i->getInstance(InjectorInjectedFixture::class);

        $this->assertSame($i, $o->injector());
    }

    /** @test */
    function it_should_inject_constant()
    {
        $i = Injector::create(null, null, ConstantInjectedFixture::class)
                ->getInstance(ConstantInjectedFixture::class);

        $i->assert($this);
    }

    /** @test */
    function it_should_inject_array()
    {
        $i = Injector::create(null, null, ArrayInjectedFixture::class)
            ->getInstance(ArrayInjectedFixture::class);

        $i->assert($this);
    }

    /** @test */
    function getInstance_should_returns_the_equals_of_get_with_key_of_type()
    {
        $i = Injector::create();
        $this->assertEquals(
            $i->getInstance(\stdClass::class),
            $i->get(Key::ofType(\stdClass::class))
        );
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_binding_can_not_be_found()
    {
        Injector::create()->getInstance(\Countable::class);
    }

    /** @test */
    function it_should_returns_env()
    {
        $env = Env::development();
        $i = Injector::create(Env::development(), null);

        $this->assertSame($env, $i->getInstance(Env::class));
    }

    /** @test */
    function it_should_returns_reflection()
    {
        $i = Injector::create();

        $this->assertInstanceOf(
            Reflection::class,
            $i->getInstance(Reflection::class)
        );
    }

    /** @test */
    function it_should_returns_codeSpace()
    {
        $i = Injector::create();

        $this->assertInstanceOf(
            CodeSpace::class,
            $i->getInstance(CodeSpace::class)
        );
    }
} 
