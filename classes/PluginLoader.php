<?php
namespace AppZap\PHPFrameworkAirbrake;

use AppZap\PHPFramework\Mvc\Router;
use AppZap\PHPFramework\SignalSlot\Dispatcher as SignalSlotDispatcher;

class PluginLoader {
  public function __construct() {
    SignalSlotDispatcher::registerSlot(
        Router::SIGNAL_ROUTE_DEFINITIONS,
        function(){}
    );
  }
}
