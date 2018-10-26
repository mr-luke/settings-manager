<?php

namespace Mrluke\Settings\Tests;

use Mrluke\Settings\Facades\Settings;

/**
 * FeatureTests for package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class FeatureTests extends TestCase
{
    public function testRegisterNewKeyForDefaultDriver()
    {
        $value = Settings::register('first', 'first value', 'string');

        $this->assertEquals('first value', $value);
    }
}
