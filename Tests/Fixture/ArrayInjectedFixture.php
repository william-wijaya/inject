<?php
namespace Plum\Inject\Tests\Fixture;

use Plum\Inject\Named;
use Plum\Inject\Provides;

class ArrayInjectedFixture
{
    private $elements;

    public function __construct(/** @Named */array $d)
    {
        $this->elements = $d;
    }

    public function assert(\PHPUnit_Framework_TestCase $testCase)
    {
        $testCase->assertEquals([self::provideElement()], $this->elements);
    }

    /** @Provides(Provides::ELEMENT) @Named */
    public static function provideElement()
    {
        return 1;
    }
} 
