<?php
namespace Plum\Inject\Tests\Fixture;

use Plum\Inject\Named;
use Plum\Inject\Provides;

class ModuleFixture
{
    /** @Provides(stdClass::class) */
    public static function provideStdClass()
    {
        return new \stdClass();
    }

    /** @Provides(\stdClass::class) @Named */
    public static function provideStdClassWithQualifier(\stdClass $s)
    {
        return $s;
    }
} 
