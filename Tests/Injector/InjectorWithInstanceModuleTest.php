<?php
namespace Plum\Inject\Tests\Injector;

use Plum\Inject\ConfigurationException;
use Plum\Inject\Injector;
use Plum\Inject\Provides;

class InjectorWithInstanceModuleTest extends \PHPUnit_Framework_TestCase
{
    private static $instance;

    /** @Provides(\stdClass::class) */
    function provideObject()
    {
        return self::$instance = new \stdClass();
    }

    function test_it_should_inject_using_provider()
    {
        $o = Injector::create(null, null, new self())
            ->getInstance(\stdClass::class);

        $this->assertSame(self::$instance, $o);
    }

    function test_it_should_throws_if_method_is_not_static_but_no_module_instance_provided()
    {
        $this->setExpectedException(ConfigurationException::class);

        Injector::create(null, null, __CLASS__)
            ->getInstance(\stdClass::class);
    }
}

