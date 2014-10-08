<?php
namespace Plum\Inject\Tests\Impl;

use Plum\Inject\Impl\Scoping;
use Plum\Inject\Singleton;
use Plum\Inject\Tests\Fixture\ScopeFixture;

class ScopingTest extends \PHPUnit_Framework_TestCase
{
    /** @test @dataProvider provideUnequalScopings */
    function it_should_returns_different_hash(Scoping $s1, Scoping $s2)
    {
        $this->assertNotEquals($s1->hashCode(), $s2->hashCode());
    }

    function provideUnequalScopings()
    {
        $s = new Singleton();
        return [
            [new Scoping($s, []), new Scoping($s, ["stdClass"])],
            [new Scoping($s, []), new Scoping(new ScopeFixture(), [])]
        ];
    }

    /** @test */
    function it_should_returns_same_hash()
    {
        $this->assertEquals(
            (new Scoping(new Singleton(), []))->hashCode(),
            (new Scoping(new Singleton(), []))->hashCode()
        );
    }
} 
