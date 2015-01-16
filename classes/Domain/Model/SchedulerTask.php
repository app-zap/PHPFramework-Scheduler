<?php
namespace AppZap\PHPFrameworkScheduler\Domain\Model;

use AppZap\PHPFramework\Domain\Model\AbstractModel;

class SchedulerTask extends AbstractModel {

  /**
   * @var string
   */
  protected $classname;

  /**
   * @var \DateTime
   */
  protected $lastExecution;

  /**
   * @return string
   */
  public function getClassname() {
    return $this->classname;
  }

  /**
   * @param string $classname
   */
  public function setClassname($classname) {
    $this->classname = $classname;
  }

  /**
   * @return \DateTime
   */
  public function getLastExecution() {
    return $this->lastExecution;
  }

  /**
   * @param \DateTime $lastExecution
   */
  public function setLastExecution($lastExecution) {
    $this->lastExecution = $lastExecution;
  }

}
