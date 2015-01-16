<?php
namespace AppZap\PHPFrameworkScheduler\Domain\Repository;

use AppZap\PHPFramework\Domain\Model\AbstractModel;
use AppZap\PHPFramework\Domain\Repository\AbstractDomainRepository;

class SchedulerTaskRepository extends AbstractDomainRepository {

  /**
   * @param string $classname
   * @return AbstractModel
   */
  public function findByClassname($classname) {
    return $this->queryOne([
      'classname' => $classname,
    ]);
  }

}
