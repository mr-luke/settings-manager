<?php

namespace Mrluke\Settings\Tests;

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
class JsonDriverTests extends TestCase
{
    public function testRegisterNewStringKey()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('json');

        $value = $bag->register('first', 'first value', 'string');

        $this->assertEquals('first value', $value);
        $this->assertEquals('string', gettype($value));

        $file = json_decode(file_get_contents(__DIR__.'/../test.json'), true);

        $this->assertEquals($file['first']['value'], $value);
    }

    public function testRegisterFloatValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('json');

        $value = $bag->register('float', 5.567, 'float');

        $this->assertEquals(5.567, $value);
        $this->assertTrue(is_float($value));

        $file = json_decode(file_get_contents(__DIR__.'/../test.json'), true);

        $this->assertEquals($file['float']['value'], $value);
    }

    public function testChangeValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('json');
        $bag->set('age', 20);

        $oldValue = $bag->get('age');
        $newValue = $bag->set('age', 21);

        $this->assertNotEquals($oldValue, $newValue);

        $file = json_decode(file_get_contents(__DIR__.'/../test.json'), true);

        $this->assertEquals($file['age']['value'], $newValue);
    }

    public function testForgetValue()
    {
        $bag = (new Manager($this->getManagerConfiguration()))->bag('json');
        $bag->set('trash', 25);

        $file = json_decode(file_get_contents(__DIR__.'/../test.json'), true);

        $this->assertTrue(isset($file['trash']));

        $bag->forget('trash');

        $file = json_decode(file_get_contents(__DIR__.'/../test.json'), true);

        $this->assertTrue(!isset($file['trash']));
    }
}
