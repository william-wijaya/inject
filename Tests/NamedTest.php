<?php
namespace Plum\Inject\Tests;

use Plum\Inject\Named;

class NamedTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_returns_string_representation()
    {
        $n = Named::name("something");

        $this->assertContains("something", (string)$n);
    }

    /** @test */
    function it_should_returns_different_object()
    {
        $this->assertNotEquals(Named::name("1"), Named::name("2"));
    }

    /** @test */
    function it_should_returns_equal_objects()
    {
        $this->assertEquals(Named::name("1"), Named::name("1"));
    }
} 
