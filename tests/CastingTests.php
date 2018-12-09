<?php

namespace Mrluke\Settings\Tests;

use Mrluke\Settings\Concerns\Castable;

/**
 * Type cast test class for package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class CastingTests extends TestCase
{
    public function testArrayType()
    {
        $array = [
            'name' => 'John',
            'last' => 'Kowalski',
        ];

        $encoded = json_encode($array);

        $mock = $this->getMock();

        $this->assertEquals(
            $array,
            $mock->castToType($encoded, 'array')
        );
    }

    public function testArrayTypeForEmptyValue()
    {
        $mock = $this->getMock();

        $this->assertEquals(
            [],
            $mock->castToType('', 'array')
        );
    }

    public function testBoolType()
    {
        $mock = $this->getMock();

        $this->assertEquals(
            true,
            $mock->castToType('true', 'bool')
        );

        $this->assertEquals(
            true,
            $mock->castToType(1, 'bool')
        );

        $this->assertEquals(
            false,
            $mock->castToType('0', 'bool')
        );

        $this->assertEquals(
            false,
            $mock->castToType(null, 'bool')
        );
    }

    public function testFloatType()
    {
        $float = 5.2345;

        $mock = $this->getMock();

        $this->assertEquals(
            $float,
            $mock->castToType('5.2345' , 'float')
        );

        $this->assertEquals(
            $float,
            $mock->castToType($float, 'float')
        );
    }

    public function testIntegerType()
    {
        $integer = 50;

        $mock = $this->getMock();

        $this->assertEquals(
            $integer,
            $mock->castToType('50', 'integer')
        );

        $this->assertEquals(
            $integer,
            $mock->castToType(50.2, 'integer')
        );
    }

    public function testJsonType()
    {
        $array = [
            'key' => 'value'
        ];

        $encoded = json_encode($array);

        $mock = $this->getMock();

        $this->assertEquals(
            $encoded,
            $mock->castToType($array, 'json')
        );

        $this->assertEquals(
            "{\"key\":\"value\"}",
            $mock->castToType($array, 'json')
        );

        $this->assertEquals(
            "{\"key\":\"value\"}",
            $mock->castToType("{\"key\":\"value\"}", 'json')
        );
    }

    public function testStringType()
    {
        $mock = $this->getMock();

        $this->assertEquals(
            '123',
            $mock->castToType(123, 'string')
        );

        $this->assertEquals(
            'string',
            gettype($mock->castToType(123, 'string'))
        );
    }

    private function getMock()
    {
        return $this->getMockForTrait(Castable::class);
    }
}
