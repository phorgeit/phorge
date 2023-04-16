<?php

final class PhorgeWorkerBulkJobTaskWorker
  extends PhorgeWorkerBulkJobWorker {

  protected function doWork() {
    $lock = $this->acquireTaskLock();

    $task = $this->loadTask();
    $status = $task->getStatus();
    switch ($task->getStatus()) {
      case PhorgeWorkerBulkTask::STATUS_WAITING:
        // This is what we expect.
        break;
      default:
        throw new PhorgeWorkerPermanentFailureException(
          pht(
            'Found unexpected task status ("%s").',
            $status));
    }

    $task
      ->setStatus(PhorgeWorkerBulkTask::STATUS_RUNNING)
      ->save();

    $lock->unlock();

    $job = $this->loadJob();
    $actor = $this->loadActor($job);

    try {
      $job->runTask($actor, $task);
      $status = PhorgeWorkerBulkTask::STATUS_DONE;
    } catch (Exception $ex) {
      phlog($ex);
      $status = PhorgeWorkerBulkTask::STATUS_FAIL;
    }

    $task
      ->setStatus($status)
      ->save();

    $this->updateJob($job);
  }

}
