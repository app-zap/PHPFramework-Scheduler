# PHPFramework Plugin: Scheduler

This package is a plugin for [PHPFramework](https://github.com/app-zap/PHPFramework).

## Installation

Include this package as requirement in your `composer.json`. For example:

    {
      ...
      "require": {
        ...
        "app-zap/phpframework-scheduler": "dev-develop"
      }
    }

To load the plugin enable it in your `settings.ini`:

    [phpframework]
    plugins.AppZap\PHPFrameworkScheduler = true

## Setup

This plugin requires a certain SQL structure. You have to include it yourself, because PHPFramework [isn't capable to load plugin SQL](https://github.com/app-zap/PHPFramework/issues/51) yet:



    SQL
