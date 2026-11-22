<?php

final class PhorgeWorkerManagementDescribeWorkflow
  extends PhabricatorWorkerManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('describe')
      ->setExamples(
        implode(
          "\n",
          array(
            '**describe** __selectors__',
          )))
      ->setSynopsis(
        pht(
          'Display information about a selected tasks.'))
      ->setArguments(
        array_merge(
          array(
            // TODO output
          ),
          $this->getTaskSelectionArguments()));
  }


  public function execute(PhutilArgumentParser $args) {

    $tasks = $this->loadTasks($args);

    if (!$tasks) {
      $this->logWarn(
        pht('NO TASKS'),
        pht('No tasks selected to describe.'));

      return 0;
    }

    $tasks_data = id(new PhabricatorWorkerTaskData())
      ->loadAllWhere(
        'ID IN (%Ld)',
        mpull($tasks, 'getDataID'));
    foreach ($tasks as $task) {
      $data = idx($tasks_data, $task->getDataID());
      if ($data) {
        $task->setData($data->getData());
      }
    }

    $rows = array();

    foreach ($tasks as $task) {
      $task_id = $task->getID();
      $rows[$task_id] = $this->buildTaskData($task);
    }


    $json = new PhutilJSON();
    PhutilConsole::getConsole()
      ->writeOut($json->encodeFormatted($rows));
  }

  private function buildTaskData(PhabricatorWorkerTask $task) {
    $task_id = $task->getID();

    if ($task->isArchived()) {
      switch ($task->getResult()) {
        case PhabricatorWorkerArchiveTask::RESULT_SUCCESS:
          $status = 'Complete';
          break;
        case PhabricatorWorkerArchiveTask::RESULT_FAILURE:
          $status = 'Failed';
          break;
        case PhabricatorWorkerArchiveTask::RESULT_CANCELLED:
          $status = 'Cancelled';
          break;
        default:
          $status = 'UnknownArchived';
          break;
        }
    } else {
      if ($task->getLeaseExpires()) {
        if ($task->getLeaseExpires() > time()) {
          $status = 'Leased';
        } else {
          $status = 'Lease Expired';
        }
      } else {
        $status = 'Queued';
      }
    }

    $data = array(
        'id' => $task_id,
        'status' => $status,
        'taskClass' => $task->getTaskClass(),
        'failureCount' => $task->getFailureCount(),
        'priority' => $task->getPriority(),
        'objectPHID' => $task->getObjectPHID(),
        'containerPHID' => $task->getContainerPHID(),
        'dataID' => $task->getDataID(),
        'data' => $task->getData(),
      );

    switch ($status) {
      case 'Leased':
        $data['leaseOwner'] = $task->getLeaseOwner();
        $data['leaseExpires'] = $task->getLeaseExpires();
        break;
      case 'Complete':
      case 'Failed':
      case 'Cancelled':
      case 'UnknownArchived':
        $data['duration'] = $task->getDuration();
        $data['archiveEpoch'] = $task->getArchivedEpoch();
        break;
    }

    return $data;
  }

}
