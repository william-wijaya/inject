<?php
namespace Plum\Inject\Tests\Injector;

use Plum\Inject\Impl\KeyFactory;
use Plum\Inject\Injector;
use Plum\Inject\Key;
use Plum\Inject\Named;
use Plum\Inject\Provides;
use Plum\Reflect\Reflection;

class InjectorWithModuleTest extends \PHPUnit_Framework_TestCase
{
    private static $dependency;

    /** @Provides(\stdClass::class) */
    static function provideDependency()
    {
        return self::$dependency = new \stdClass();
    }

    /** @Provides(\stdClass::class) @Named */
    static function provideObject(\stdClass $object)
    {
        $object->named = true;

        return $object;
    }

    function test_it_should_returns_from_provider()
    {
        $o = Injector::create(null, null, __CLASS__)
            ->getInstance(\stdClass::class);

        $this->assertSame(self::$dependency, $o);
    }

    function test_it_should_returns_instance_from_provider_with_dependency()
    {
        $r = Reflection::create();
        $m = $r->ofType(__CLASS__)->getMethod("provideObject");

        $k = KeyFactory::keyOfProvider($m);
        $o = Injector::create(null, null, __CLASS__)
            ->get($k);

        $this->assertEquals(true, $o->named);
    }
} 
