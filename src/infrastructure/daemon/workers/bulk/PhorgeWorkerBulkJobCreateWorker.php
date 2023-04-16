<?php

final class PhorgeWorkerBulkJobCreateWorker
  extends PhorgeWorkerBulkJobWorker {

  protected function doWork() {
    $lock = $this->acquireJobLock();

    $job = $this->loadJob();
    $actor = $this->loadActor($job);

    $status = $job->getStatus();
    switch ($status) {
      case PhorgeWorkerBulkJob::STATUS_WAITING:
        // This is what we expect. Other statuses indicate some kind of race
        // is afoot.
        break;
      default:
        throw new PhorgeWorkerPermanentFailureException(
          pht(
            'Found unexpected job status ("%s").',
            $status));
    }

    $tasks = $job->createTasks();
    foreach ($tasks as $task) {
      $task->save();
    }

    $this->updateJobStatus(
      $job,
      PhorgeWorkerBulkJob::STATUS_RUNNING);

    $lock->unlock();

    foreach ($tasks as $task) {
      PhorgeWorker::scheduleTask(
        'PhorgeWorkerBulkJobTaskWorker',
        array(
          'jobID' => $job->getID(),
          'taskID' => $task->getID(),
        ),
        array(
          'priority' => PhorgeWorker::PRIORITY_BULK,
        ));
    }

    $this->updateJob($job);
  }

}
