<?php
namespace AppZap\PHPFrameworkScheduler\Controller;

use AppZap\PHPFramework\Mvc\AbstractController;

class SchedulerController extends AbstractController {

  public function cli() {
    return 'scheduler invoked';
  }

}
