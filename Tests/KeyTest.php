<?php
namespace Plum\Inject\Tests;

use Plum\Inject\Key;
use Plum\Inject\Named;

class KeyTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    function it_should_returns_type_key()
    {
        $k = Key::ofType(__CLASS__);

        $this->assertTrue($k->isType());
    }

    /** @test */
    function it_should_returns_type()
    {
        $k = Key::ofType(__CLASS__);

        $this->assertEquals(__CLASS__, $k->type());
    }

    /** @test */
    function it_should_returns_constant_key()
    {
        $k = Key::ofConstant(Named::name(""));

        $this->assertTrue($k->isConstant());
    }

    /** @test */
    function it_should_returns_array_key()
    {
        $k = Key::ofArray(Named::name(""));

        $this->assertTrue($k->isArray());
    }

    /** @test */
    function it_should_returns_qualifier()
    {
        $q = Named::name("");
        $k = Key::ofConstant($q);

        $this->assertSame($q, $k->qualifier());
    }

    /** @test @dataProvider provideKeysWithSameHashCode */
    function it_should_returns_same_hash_code(Key $k1, Key $k2)
    {
        $this->assertEquals($k1->hashCode(), $k2->hashCode());
    }

    function provideKeysWithSameHashCode()
    {
        return [
            [Key::ofArray(Named::name("")), Key::ofArray(Named::name(""))],
            [Key::ofConstant(Named::name("")), Key::ofConstant(Named::name(""))],
            [Key::ofType(\stdClass::class), Key::ofType(\stdClass::class)],
            [Key::ofType(\stdClass::class, Named::name("")), Key::ofType(\stdClass::class, Named::name(""))],
        ];
    }

    /** @test @dataProvider provideKeysWithDifferentHashCode */
    function it_should_returns_different_hash_code(Key $k1, Key $k2)
    {
        $this->assertNotEquals($k1->hashCode(), $k2->hashCode());
    }

    function provideKeysWithDifferentHashCode()
    {
        return [
            [Key::ofArray(Named::name("")), Key::ofArray(Named::name("x"))],
            [Key::ofConstant(Named::name("")), Key::ofConstant(Named::name("x"))],
            [Key::ofArray(Named::name("")), Key::ofConstant(Named::name(""))],
            [Key::ofArray(Named::name("")), Key::ofConstant(Named::name("x"))],

            [Key::ofType(\stdClass::class), Key::ofType(\DateTime::class)],
            [Key::ofType(\stdClass::class), Key::ofType(\stdClass::class, Named::name(""))],
            [Key::ofType(\stdClass::class, Named::name("")), Key::ofType(\stdClass::class, Named::name("x"))]
        ];
    }

    /** @test */
    function it_should_ignore_global_namespace_separator_for_type()
    {
        $this->assertEquals(
            Key::ofType("stdClass"), Key::ofType("\\stdClass")
        );
    }

    /** @test @dataProvider provideKeysToBeCastToString */
    function it_should_returns_human_readable_string(Key $k)
    {
        $this->assertContains($k->type(), (string)$k);
        if ($k->qualifier())
            $this->assertContains((string)$k->qualifier(), (string)$k);
    }

    function provideKeysToBeCastToString()
    {
        return [
            [Key::ofArray(new Named())],
            [Key::ofConstant(new Named())],
            [Key::ofType(\stdClass::class)],
            [Key::ofType(\stdClass::class, new Named())],
        ];
    }
} 
