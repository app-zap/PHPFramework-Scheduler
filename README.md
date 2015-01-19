# PHPFramework Plugin: Scheduler

This package is a plugin for [PHPFramework](https://github.com/app-zap/PHPFramework).

When writing applications you often want to schedule tasks for later or for regular execution.
Instead of configuring a cronjob for each task, you can use the scheduler as a proxy.

## Installation

Include this package as requirement in your `composer.json`. For example:

    {
      ...
      "require": {
        ...
        "app-zap/phpframework-scheduler": "dev-develop"
      }
    }

This plugin requires a certain SQL structure. You have to include it yourself, because PHPFramework [isn't capable to load plugin SQL](https://github.com/app-zap/PHPFramework/issues/51) yet:

    CREATE TABLE `schedulertask` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `classname` varchar(255) NOT NULL DEFAULT '',
      `last_execution` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `classname` (`classname`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

*Hint: If you're using the PHPFramework's database migrator, copy the above statement to a `.sql` file to have it applied automatically when needed.*

Setup a cronjob like this:

    */5 * * * * php path/to/your/projects/index.php invokeScheduler

## Configuration

You can set up tasks in your `settings.ini`:

    [phpframework-scheduler]
    tasks.MyVendor\MyApp\Task\DailyStatisticsMail = "0 18 * * *"

This calls the `DailyStatisticsMail` class daily at 6pm. The class has to implement the
`\AppZap\PHPFrameworkScheduler\TaskExecutorInterface`, which means it has to implement the `execute()`
method.
