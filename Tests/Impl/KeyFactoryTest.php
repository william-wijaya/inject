<?php
namespace Plum\Inject\Tests\Impl;

use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\KeyFactory;
use Plum\Inject\Key;
use Plum\Reflect\Reflection;
use Plum\Inject\Named;
use Plum\Inject\Provides;

class KeyFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_returns_type_key(\stdClass $d = null)
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        $k = KeyFactory::keyOfDependency($p);

        $this->assertEquals(Key::ofType($p->getClass()->name), $k);
    }

    /** @test */
    function it_should_returns_type_key_accordingly(/** @Named */\stdClass $d = null)
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        $k = KeyFactory::keyOfDependency($p);

        $this->assertEquals(
            Key::ofType($p->getClass()->name, new Named()), $k
        );
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_parameter_is_not_bindable($notBindable = null)
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        KeyFactory::keyOfDependency($p);
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_array_dependency_not_annotated_with_qualifier(
        array $d = []
    )
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        KeyFactory::keyOfDependency($p);
    }

    /** @test */
    function it_should_returns_array_key_accordingly(/** @Named */array $d = [])
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        $k = KeyFactory::keyOfDependency($p);

        $this->assertEquals(Key::ofArray(new Named()), $k);
    }

    /**
     * @test
     * @expectedException \Plum\Inject\ConfigurationException
     */
    function it_should_throws_if_constant_dependency_not_annotated_with_qualifier(
        $d = null
    )
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        KeyFactory::keyOfDependency($p);
    }

    /** @test */
    function it_should_returns_constant_key_accordingly(/** @Named */$d = null)
    {
        $p = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__)
            ->getParameters()[0];

        $k = KeyFactory::keyOfDependency($p);

        $this->assertEquals(Key::ofConstant(new Named()), $k);
    }

    /** @Provides */
    function test_it_should_throws_if_constant_provider_not_annotated_with_qualifier()
    {
        $this->setExpectedException(ConfigurationException::class);

        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        KeyFactory::keyOfProvider($m);
    }

    /** @Provides(Provides::ELEMENT) */
    function test_it_should_throws_if_element_provider_not_annotated_with_qualifier()
    {
        $this->setExpectedException(ConfigurationException::class);

        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        KeyFactory::keyOfProvider($m);
    }

    /** @Provides(\stdClass::class) */
    function test_it_should_returns_provider_type_key()
    {
        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        $k = KeyFactory::keyOfProvider($m);

        $this->assertEquals(Key::ofType(\stdClass::class), $k);
    }

    /** @Provides(\stdClass::class) @Named */
    function test_it_should_returns_provider_type_key_with_qualifier()
    {
        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        $k = KeyFactory::keyOfProvider($m);

        $this->assertEquals(Key::ofType(\stdClass::class, new Named()), $k);
    }

    /** @Provides @Named */
    function test_it_should_returns_provider_constant_key_with_qualifier()
    {
        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        $k = KeyFactory::keyOfProvider($m);

        $this->assertEquals(Key::ofConstant(new Named()), $k);
    }

    /** @Provides(Provides::ELEMENT) @Named */
    function test_it_should_returns_provider_element_key_with_qualifier()
    {
        $m = Reflection::create()
            ->ofType(__CLASS__)
            ->getMethod(__FUNCTION__);

        $k = KeyFactory::keyOfProvider($m);

        $this->assertEquals(Key::ofArray(new Named()), $k);
    }
} 
