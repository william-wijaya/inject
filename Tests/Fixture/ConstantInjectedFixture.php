<?php
namespace Plum\Inject\Tests\Fixture;

use Plum\Inject\Provides;
use Plum\Inject\Named;

class ConstantInjectedFixture
{
    private $constant;

    public function __construct(/** @Named */$const)
    {
        $this->constant = $const;
    }

    public function constant()
    {
        return $this->constant;
    }

    public function assert(\PHPUnit_Framework_TestCase $testCase)
    {
        $testCase->assertEquals(M_PI, $this->constant);
    }

    /** @Provides @Named() */
    public static function provideConstant()
    {
        return M_PI;
    }
} 
