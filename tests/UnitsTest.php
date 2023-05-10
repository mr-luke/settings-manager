<?php

namespace Mrluke\Settings\Tests;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mrluke\Configuration\Contracts\ArrayHost;
use Mrluke\Configuration\Host;
use Mrluke\Settings\Contracts\Bag;
use Mrluke\Settings\Contracts\Cachable;
use Mrluke\Settings\Contracts\Driver;
use Mrluke\Settings\Drivers\Database;
use Mrluke\Settings\Drivers\Json;
use Mrluke\Settings\Manager;
use Mrluke\Settings\SettingsBag;
use PHPUnit\Framework\TestCase;

/**
 * UnitTests for package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class UnitsTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testConfigurationPassesContract()
    {
        $array = ['test' => 'test'];

        $config = new Host($array);

        $this->assertTrue($config instanceof ArrayHost);
    }

    public function testDatabaseDriverPassesContracts()
    {
        $driver = new Database(
            ['connection' => '', 'table' => ''],
            'name',
            ['cache' => '', 'lifetime' => '']
        );

        $this->assertTrue($driver instanceof Cachable);
        $this->assertTrue($driver instanceof Driver);
    }

    public function testJsonDriverPassesContracts()
    {
        $driver = new Json(
            ['path' => '', 'file' => ''], 'name', []
        );

        $this->assertTrue($driver instanceof Driver);
    }

    public function testManagerReturnsBag()
    {
        $configData = [
            'bags' => [
                'general' => [
                    'driver'   => 'database',
                    'cache'    => true,
                    'lifetime' => 60,
                ],
            ],
            'drivers' => [
                'database' => [
                    'class'      => Database::class,
                    'connection' => 'sqlite',
                    'table'      => 'settings',
                ],
            ],
        ];

        $manager = new Manager(new Host($configData));

        $shouldBag = $manager->bag('general');

        $this->assertTrue($shouldBag instanceof Bag);
    }

    public function testThrowsExceptionForDriverWithBadConfiguration()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Database(['test' => 'bad'], 'name', []);
        new Json(['bad' => 'very bad', 'name', []]);
    }

    public function testThrowsFatalErrorForDriverWithoutConfiguration()
    {
        $this->expectException(\ArgumentCountError::class);

        new Database();
    }

    public function testBagRegisterReturnsValue()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('insert')->once()
                   ->with('key', 'value', 'string')
                   ->andReturn('value');

        $bag = new SettingsBag($driverMock, 'general');
        $bag->register('key', 'value', 'string');
    }

    public function testBagSetReturnsValue()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn(['key' => 'value']);

        $driverMock->shouldReceive('update')
                   ->once()
                   ->with('key', 'new_value')
                   ->andReturn('new_value');

        $bag = new SettingsBag($driverMock, 'general');
        $bag->set('key', 'new_value');
    }

    public function testBagSetShouldCallRegisterForNewKey()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn([]);

        $driverMock->shouldReceive('insert')->once();

        $bag = new SettingsBag($driverMock, 'general');
        $bag->set('key2', 'value2');
    }

    public function testBagGetReturnsNullForNotExistingKey()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn([]);

        $bag = new SettingsBag($driverMock, 'general');
        $value = $bag->get('key');

        $this->assertEquals($value, null);
    }

    public function testBagGetReturnValue()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn(['key' => 2.56]);

        $bag = new SettingsBag($driverMock, 'general');
        $value = $bag->get('key');

        $this->assertEquals($value, 2.56);
    }

    public function testBagForgetValue()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn(['key' => 'has']);

        $driverMock->shouldReceive('delete')
                   ->with('key');

        $bag = new SettingsBag($driverMock, 'general');
        $value = $bag->get('key');

        $this->assertEquals($value, 'has');

        $bag->forget('key');
        $newValue = $bag->get('key');

        $this->assertEquals($newValue, null);
    }

    public function testBagGetReturnsDefaultValue()
    {
        $driverMock = Mockery::mock('FakeDriver', Driver::class);
        $driverMock->shouldReceive('fetch')->once()
                   ->andReturn([]);

        $bag = new SettingsBag($driverMock, 'general');
        $value = $bag->get('key', 'default value');

        $this->assertEquals($value, 'default value');
    }
}
