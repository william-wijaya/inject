<?php
namespace Plum\Inject\Tests\Impl\Binder;

use Plum\Inject\Binding;
use Plum\Inject\Impl\Binder\JustInTimeBinder;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Key;
use Plum\Inject\Tests\Fixture\NotInstantiableFixture;
use Plum\Inject\Tests\Fixture\TypeWithUnbindableDependency;
use Plum\Reflect\Reflection;
use Plum\Reflect\Type;

class JustInTimeBinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_type_is_interface()
    {
        $jit = new JustInTimeBinder(new Bindings());
        $t = Reflection::create()->ofType(\Countable::class);

        $jit->bind($t);
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_type_is_abstract_class()
    {
        $jit = new JustInTimeBinder(new Bindings());
        $t = Reflection::create()->ofType(\FilterIterator::class);

        $jit->bind($t);
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_type_is_not_instantiable()
    {
        $jit = new JustInTimeBinder(new Bindings());
        $t = Reflection::create()->ofType(NotInstantiableFixture::class);

        $jit->bind($t);
    }

    /** @test */
    function it_should_returns_binding()
    {
        $k = Key::ofType(\stdClass::class);

        $jit = new JustInTimeBinder(new Bindings());
        $t = Reflection::create()->ofType($k->type());

        $binding = $jit->bind($t);

        $this->assertInstanceOf(Binding::class, $binding);
        $this->assertEquals($k, $binding->key());
    }

    /** @test */
    function it_should_binds_type()
    {
        $bs = new Bindings();
        $jit = new JustInTimeBinder($bs);
        $t = Reflection::create()->ofType(\stdClass::class);

        $binding = $jit->bind($t);

        $this->assertEquals(
            $binding, $bs->get(Key::ofType(\stdClass::class))
        );
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_binding_for_dependency_can_not_be_found()
    {
        $jit = new JustInTimeBinder(new Bindings());
        $t = Reflection::create()->ofType(TypeWithUnbindableDependency::class);

        $jit->bind($t);
    }
} 
