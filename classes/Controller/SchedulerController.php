<?php
namespace AppZap\PHPFrameworkScheduler\Controller;

use AppZap\PHPFramework\Configuration\Configuration;
use AppZap\PHPFramework\Mvc\AbstractController;
use AppZap\PHPFramework\Mvc\DispatchingInterruptedException;
use AppZap\PHPFramework\Mvc\HttpStatus;
use AppZap\PHPFrameworkScheduler\Domain\Model\SchedulerTask;
use AppZap\PHPFrameworkScheduler\Domain\Repository\SchedulerTaskRepository;
use AppZap\PHPFrameworkScheduler\TaskExecutorInterface;
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
        }
        echo 'Check if ' . $schedulerTask->getClassname() . ' is due: ';
        $invokeTask = $this->checkInvokeTask($schedulerTask, $timing);
        echo ($invokeTask ? 'Yes' : 'No') . "\n";
        if ($invokeTask) {
          $this->invokeTask($schedulerTask);
        }
      }
    }
    return 'Invoked scheduler successfully.';
  }

  public function get() {
    if (Configuration::get('phpframework-scheduler', 'enable_get_request', FALSE)) {
      echo '<pre>';
      return $this->cli();
    } else {
      HttpStatus::setStatus(
        HttpStatus::STATUS_403_FORBIDDEN
      );
      HttpStatus::sendHeaders();
      throw new DispatchingInterruptedException();
    }
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
    if ($cron->getPreviousRunDate()->getTimestamp() > $schedulerTask->getLastExecution()) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param SchedulerTask $schedulerTask
   */
  protected function invokeTask($schedulerTask) {
    $classname = $schedulerTask->getClassname();
    echo 'Invoking ' . $classname . "\n";
    $taskExecutor = new $classname();
    if (!$taskExecutor instanceof TaskExecutorInterface) {
      echo 'Warning! Class ' . $classname . ' doesn\'t implement the TaskExecutorInterface.' . "\n";
      return;
    }
    echo $taskExecutor->execute();
    $schedulerTask->setLastExecution(new \DateTime('now'));
    $this->schedulerTaskRespository->save($schedulerTask);
  }

}
