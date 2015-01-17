<?php
namespace AppZap\PHPFrameworkScheduler;

use AppZap\PHPFramework\Mvc\Router;
use AppZap\PHPFramework\SignalSlot\Dispatcher as SignalSlotDispatcher;

class PluginLoader {
  public function __construct() {
    SignalSlotDispatcher::registerSlot(
        Router::SIGNAL_ROUTE_DEFINITIONS,
        function(&$routes){
          if (php_sapi_name() === 'cli') {
            $routes['invokeScheduler'] = 'AppZap\\PHPFrameworkScheduler\\Controller\\SchedulerController';
          }
        }
    );
  }
}

new PluginLoader();