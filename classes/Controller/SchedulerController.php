<?php
namespace AppZap\PHPFrameworkScheduler\Controller;

use AppZap\PHPFramework\Configuration\Configuration;
use AppZap\PHPFramework\Mvc\AbstractController;
use AppZap\PHPFrameworkScheduler\Domain\Model\SchedulerTask;
use AppZap\PHPFrameworkScheduler\Domain\Repository\SchedulerTaskRepository;

class SchedulerController extends AbstractController {

  /**
   * @var SchedulerTaskRepository
   */
  protected $schedulerTaskRespository;

  public function initialize() {
    $this->schedulerTaskRespository = SchedulerTaskRepository::getInstance();
  }

  public function cli() {
    $tasks = Configuration::getSection('phpframework-scheduler', 'tasks');
    if (is_array($tasks)) {
      foreach ($tasks as $classname => $timing) {
        if (!class_exists($classname)) {
          echo 'Warning! Class ' . $classname . ' couldn\'t be loaded.' . "\n";
          continue;
        }
        $schedulerTask = $this->schedulerTaskRespository->findByClassname($classname);
        if (!$schedulerTask instanceof SchedulerTask) {
          $schedulerTask = new SchedulerTask();
          $schedulerTask->setClassname($classname);
          $this->schedulerTaskRespository->save($schedulerTask);
        }
        $this->invokeTask($schedulerTask);
      }
    }
    return 'Invoked scheduler successfully.';
  }

  public function get() {
    return $this->cli();
  }

  /**
   * @param SchedulerTask $schedulerTask
   */
  protected function invokeTask(SchedulerTask $schedulerTask) {

  }

}
