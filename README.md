Settings Manager - Laravel multibag settings Package.
==============

[![Latest Stable Version](https://poser.pugx.org/mr-luke/settings-manager/v/stable)](https://packagist.org/packages/mr-luke/settings-manager)
[![Total Downloads](https://poser.pugx.org/mr-luke/settings-manager/downloads)](https://packagist.org/packages/mr-luke/settings-manager)
[![License](https://poser.pugx.org/mr-luke/settings-manager/license)](https://packagist.org/packages/mr-luke/settings-manager)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mr-luke/settings-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mr-luke/settings-manager/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/mr-luke/settings-manager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mr-luke/settings-manager/build-status/master)

This package provides settings manamgement to your Laravel app with support of multiple setting bags with typed value.

* [Getting Started](#getting-started)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
* [Plans](#plans)

## Getting Started

Setting Manager supported versions:
* Laravel 10
* Laravel 9
* Laravel 8

## Installation

To install through composer, simply put the following in your composer.json file and run `composer update`

```json
{
    "require": {
        "mr-luke/settings-manager": "~1.0"
    }
}
```
Or use the following command

```bash
composer require "mr-luke/settings-manager"
```

Next, add the service provider to `app/config/app.php`

```
Mrluke\Settings\SettingsServiceProvider::class,
```
*Note: Package is auto-discoverable!*

## Configuration

To use `SettingsManager` you need to setup your `Bags` first. Add your own one to the [config file](config/settings-manager.php) `bags`:

```php
'bags' => [
    'general' => [ // Given key is used as a Bag name
        'driver'   => 'database', // One of available drivers
        'cache'    => true, // Should be Cached
        'lifetime' => 60 // Cache lifetime in minutes
	],
],
```

You can setup different database connections or tables by new `driver` or different `json` files in:

```php
'drivers' => [
    'database' => [
        'class'      => \Mrluke\Settings\Drivers\Database::class,
        'connection' => 'mysql',
        'table'      => 'settings',
    ],

    'json' => [
        'class' => \Mrluke\Settings\Drivers\Json::class,
        'path'  => base_path('storage/app/settings/'),
        'file'  => 'settings.json',
    ]
],
```

You can also publish config file via command:
```bash
php artisan vendor:publish
```

## Usage

### Facade

You can access to `Manager` and your `Bags` using `Mrluke\Settings\Facades\Settings`.

### Type

`SettingsManager` is a type casted tool that care about it during the whole process.

Example: *You can pass `string 5.567` to `set` method for `ratio` setting (`float`) and `SettingsManager` will cast the value to `float 5.567` behind the scean.*

Whereever you ask for certain key, it will always be correct type. But all begins at the point of `registering`...

### Registering new index

To register new value use:
```php
$value = Settings::register(string $key, $value, string $type);
```
This method returns given `$value` casted to given `$type`.

### Accessing index

To get value use:
```php
$value = Settings::get(string $key, $default = null);
```
This method returns `$default` in case given `$key` is not present in the `Bag`. Otherwise `$value` is casted to registered `type`.

### Setting new value of index

To set new value use:
```php
$value = Settings::set(string $key, $value);
```
This method returns `$value` casted to registered `type`. If given `$key` is not present, it will automaticaly call `register` method with auto-detected `type`.

*This method shoud not be use to register settings in general! Use `Eventing` to detect all needed settings during development.*

### Forgeting  an index

To forget an index use:
```php
Settings::forget(string $key);
```

### Default `Bag` vs specified

To access specific `Bag` use bag accessor method:
 ```php
$value = Settings::bag(string $name)->get(string $key, $default = null);
```

### Helper

You can access to `SettingsManager` via helper function:
```php
// Get index
settings(string $key);
// Set value
settings([string $key => $value]);
// Get instance and perform action
settings()->forget(string $key);
```

### Events

`SettingsManager` provides you a list of `pre` and `post` action events to help you handle different situations.

Example: *You can use `Mrluke\Settings\Events\Registered` event to prepare full list of production settings.*

Namespace `Mrluke\Settings\Events`:

* `Forgeting`
* `Forgot`
* `Loading`
* `Loaded`
* `Registering`
* `Registered`
* `Updating`
* `Updated`

### JSON Structure

`SettingsManager` provides JSON Driver for configurations that can be share with front-end apps. The structure looks like this:

```json
{
  "key": {
    "type": "<type>",
    "value": "<value>"
  },
  "another": {
    "type": "<type>",
    "value": "<value>"
  }
}
```

## Plans

* Artisan Commands
* Blade helpers for `bool` keys
* Additional option to store `default` value from `get()` method
