<?php

namespace Mrluke\Settings\Tests;

use Illuminate\Support\Facades\Event;
use Mrluke\Settings\Events\Forgeting;
use Mrluke\Settings\Events\Forgot;
use Mrluke\Settings\Events\Loaded;
use Mrluke\Settings\Events\Loading;
use Mrluke\Settings\Events\Registered;
use Mrluke\Settings\Events\Registering;
use Mrluke\Settings\Events\Updated;
use Mrluke\Settings\Events\Updating;
use Mrluke\Settings\Manager;

/**
 * FeatureTests for package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class DatabaseDriverTests extends TestCase
{
    public function testRegisterStringValue()
    {
        Event::fake();

        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $value = $bag->register('first', 'first value', 'string');

        Event::assertDispatched(Registering::class);
        Event::assertDispatched(Registered::class);

        $this->assertEquals('first value', $value);
        $this->assertEquals('string', gettype($value));

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'first',
            'type'  => 'string',
            'value' => 'first value',
        ]);
    }

    public function testRegisterFloatValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $value = $bag->register('second_float', 5.567, 'float');

        $this->assertEquals(5.567, $value);
        $this->assertTrue(is_float($value));

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'second_float',
            'type'  => 'float',
            'value' => '5.567',
        ]);
    }

    public function testRegisterIntegerValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $value = $bag->register('third_integer', 5, 'integer');

        $this->assertEquals(5, $value);
        $this->assertTrue(is_int($value));
    }

    public function testRegisterBoolValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $value = $bag->register('fourth_bool', true, 'bool');

        $this->assertTrue($value);
        $this->assertTrue(is_bool($value));

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'fourth_bool',
            'type'  => 'bool',
            'value' => true,
        ]);
    }

    public function testRegisterArrayValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $array = [
            'first'  => 'value',
            'second' => 'value',
        ];

        $value = $bag->register('fifth_array', $array, 'array');

        $this->assertSame($array, $value);
        $this->assertTrue(is_array($value));

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'fifth_array',
            'type'  => 'array',
            'value' => json_encode($array),
        ]);
    }

    public function testRegisterJsonValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $json = '{"first":"value","second":"value"}';

        $value = $bag->register('sixth_array', $json, 'json');

        $this->assertSame($json, $value);
    }

    public function testRegisterBadTypeValue()
    {
        $this->expectException(\InvalidArgumentException::class);

        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $bag->register('bad', 'not good type', 'bad type');
    }

    public function testRegisterCastStringToFloat()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');

        $value = $bag->register('seventh_casted', '5.567', 'float');

        $this->assertEquals(5.567, $value);
        $this->assertTrue(is_float($value));
    }

    public function testGetExistsKey()
    {
        Event::fake();

        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');
        $bag->set('key', 5.567);

        $value = $bag->get('key');

        Event::assertDispatched(Loading::class);
        Event::assertDispatched(Loaded::class);

        $this->assertEquals(5.567, $value);
    }

    public function testChangeValue()
    {
        Event::fake();

        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');
        $bag->set('age', 20);

        $oldValue = $bag->get('age');
        $newValue = $bag->set('age', 21);

        Event::assertDispatched(Updating::class);
        Event::assertDispatched(Updated::class);

        $this->assertNotEquals($oldValue, $newValue);

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'age',
            'type'  => 'integer',
            'value' => 21,
        ]);
    }

    public function testForgetValue()
    {
        Event::fake();

        $bag = (new Manager($this->getManagerConfiguration()))->bag('database');
        $bag->set('age_1', 20);
        $bag->set('age_2', 25);

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'age_1',
            'type'  => 'integer',
            'value' => 20,
        ]);

        $bag->forget('age_1');

        Event::assertDispatched(Forgeting::class);
        Event::assertDispatched(Forgot::class);

        $this->assertDatabaseMissing('settings', [
            'bag' => 'database',
            'key' => 'age_1',
        ]);

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'age_2',
            'type'  => 'integer',
            'value' => 25,
        ]);
    }

    public function testGetSameKeyFromDifferentBags()
    {
        $bag1 = (new Manager($this->getManagerConfiguration()))->bag('database');
        $bag2 = (new Manager($this->getManagerConfiguration()))->bag('other');

        $bag1->register('age', 20, 'integer');
        $bag2->register('age', '25', 'integer');

        $value1 = $bag1->get('age');
        $value2 = $bag2->get('age');

        $this->assertNotEquals($value1, $value2);

        $this->assertDatabaseHas('settings', [
            'bag'   => 'database',
            'key'   => 'age',
            'type'  => 'integer',
            'value' => 20,
        ]);

        $this->assertDatabaseHas('settings', [
            'bag'   => 'other',
            'key'   => 'age',
            'type'  => 'integer',
            'value' => 25,
        ]);
    }
}
