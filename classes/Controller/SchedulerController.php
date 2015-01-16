<?php
namespace AppZap\PHPFrameworkScheduler\Controller;

use AppZap\PHPFramework\Configuration\Configuration;
use AppZap\PHPFramework\Mvc\AbstractController;
use AppZap\PHPFrameworkScheduler\Domain\Model\SchedulerTask;
use AppZap\PHPFrameworkScheduler\Domain\Repository\SchedulerTaskRepository;
use Cron\CronExpression;

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
        $invokeTask = $this->checkInvokeTask($schedulerTask, $timing);
      }
    }
    return 'Invoked scheduler successfully.';
  }

  public function get() {
    return $this->cli();
  }

  /**
   * @param SchedulerTask $schedulerTask
   * @param mixed $timing Crontab string or timestamp
   * @return bool
   */
  protected function checkInvokeTask(SchedulerTask $schedulerTask, $timing) {
    $now = time();
    if (is_numeric($timing)) {
      if ($timing > $schedulerTask->getLastExecution() && $timing < $now) {
        return TRUE;
      }
      return FALSE;
    }
    if ($schedulerTask->getLastExecution() == 0) {
      return TRUE;
    }
    $cron = CronExpression::factory($timing);
    if ($cron->getPreviousRunDate() > $schedulerTask->getLastExecution()) {
      return TRUE;
    }
    return FALSE;
  }

}
