<?php
namespace Plum\Inject\Tests\Impl\Binder;

use Plum\Inject\Binding;
use Plum\Inject\Impl\Binder\ModuleBinder;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\KeyFactory;
use Plum\Inject\Tests\Fixture\ModuleFixture;
use Plum\Reflect\Reflection;
use Plum\Inject\Binding\ProviderBinding;

class ModuleBinderTest extends \PHPUnit_Framework_TestCase
{
    function test_it_should_binds_module()
    {
        $r = Reflection::create();
        $bs = new Bindings();
        $mb = new ModuleBinder($bs, $r);

        $mb->bind(ModuleFixture::class, 0);

        $m = $r->ofType(ModuleFixture::class)->getMethod("provideStdClass");
        $k = KeyFactory::keyOfProvider($m);

        $this->assertNotNull($bs->get($k));
        $this->assertInstanceOf(Binding::class, $bs->get($k));
    }

    /** @test */
    function it_should_binds_module_instance()
    {
        $r = Reflection::create();
        $bs = new Bindings();
        $mb = new ModuleBinder($bs, $r);

        $mb->bind(new ModuleFixture(), 0);

        $m = $r->ofType(ModuleFixture::class)->getMethod("provideStdClass");
        $k = KeyFactory::keyOfProvider($m);

        $this->assertNotNull($bs->get($k));
        $this->assertInstanceOf(Binding::class, $bs->get($k));
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_there_is_no_provider_in_the_module()
    {
        $r = Reflection::create();
        $mb = new ModuleBinder(new Bindings(), $r);

        $mb->bind(\stdClass::class, 0);
    }

    /** @test */
    function it_should_bind_provider_with_dependency()
    {
        $r = Reflection::create();
        $bs = new Bindings();

        $mb = new ModuleBinder($bs, $r);
        $mb->bind(ModuleFixture::class, 0);

        $m = $r->ofType(ModuleFixture::class)
            ->getMethod("provideStdClassWithQualifier");
        $k = KeyFactory::keyOfProvider($m);

        $this->assertNotNull($bs->get($k));
        $this->assertInstanceOf(ProviderBinding::class, $bs->get($k));
        $this->assertCount(1, $bs->get($k)->dependencies());
    }
} 
