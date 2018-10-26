Settings Manager - Laravel multibag settings Package.
==============

[![Latest Stable Version](https://poser.pugx.org/mr-luke/settings-manager/v/stable)](https://packagist.org/packages/mr-luke/settings-manager)
[![Total Downloads](https://poser.pugx.org/mr-luke/settings-manager/downloads)](https://packagist.org/packages/mr-luke/settings-manager)
[![License](https://poser.pugx.org/mr-luke/settings-manager/license)](https://packagist.org/packages/mr-luke/settings-manager)
![StyleCI](https://github.styleci.io/repos/153353559/shield?branch=master)

This package provides settings manager that supports multiple setting bags.

* [Getting Started](#getting-started)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
* [Plans](#plans)

## Getting Started

Setting Manager has been developed using Laravel 5.5. It's recommended to test it out before using with previous versions. PHP >= 7.1.3 is required.

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

## Configuration

You can see the options for further customization in the [config file](config/settings-manager.php).

You can also publish config file
```bash
php artisan vendor:publish
```

## Usage

### Configuration

To use `Manager` you need to setup your Bags.

### Accessing Bag

To access specific `Mrluke\Settings\Contracts\Bag` use method **`bag(string $name)`** 

## Plans

JSON Driver in progress...
