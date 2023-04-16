<?php

abstract class PhorgeWorkerBulkJobWorker
  extends PhorgeWorker {

  final protected function acquireJobLock() {
    return PhorgeGlobalLock::newLock('bulkjob.'.$this->getJobID())
      ->lock(15);
  }

  final protected function acquireTaskLock() {
    return PhorgeGlobalLock::newLock('bulktask.'.$this->getTaskID())
      ->lock(15);
  }

  final protected function getJobID() {
    $data = $this->getTaskData();
    $id = idx($data, 'jobID');
    if (!$id) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Worker has no job ID.'));
    }
    return $id;
  }

  final protected function getTaskID() {
    $data = $this->getTaskData();
    $id = idx($data, 'taskID');
    if (!$id) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Worker has no task ID.'));
    }
    return $id;
  }

  final protected function loadJob() {
    $id = $this->getJobID();
    $job = id(new PhorgeWorkerBulkJobQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withIDs(array($id))
      ->executeOne();
    if (!$job) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Worker has invalid job ID ("%s").', $id));
    }
    return $job;
  }

  final protected function loadTask() {
    $id = $this->getTaskID();
    $task = id(new PhorgeWorkerBulkTask())->load($id);
    if (!$task) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Worker has invalid task ID ("%s").', $id));
    }
    return $task;
  }

  final protected function loadActor(PhorgeWorkerBulkJob $job) {
    $actor_phid = $job->getAuthorPHID();
    $actor = id(new PhorgePeopleQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($actor_phid))
      ->executeOne();
    if (!$actor) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Worker has invalid actor PHID ("%s").', $actor_phid));
    }

    $can_edit = PhorgePolicyFilter::hasCapability(
      $actor,
      $job,
      PhorgePolicyCapability::CAN_EDIT);

    if (!$can_edit) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('Job actor does not have permission to edit job.'));
    }

    // Allow the worker to fill user caches inline; bulk jobs occasionally
    // need to access user preferences.
    $actor->setAllowInlineCacheGeneration(true);

    return $actor;
  }

  final protected function updateJob(PhorgeWorkerBulkJob $job) {
    $has_work = $this->hasRemainingWork($job);
    if ($has_work) {
      return;
    }

    $lock = $this->acquireJobLock();

    $job = $this->loadJob();
    if ($job->getStatus() == PhorgeWorkerBulkJob::STATUS_RUNNING) {
      if (!$this->hasRemainingWork($job)) {
        $this->updateJobStatus(
          $job,
          PhorgeWorkerBulkJob::STATUS_COMPLETE);
      }
    }

    $lock->unlock();
  }

  private function hasRemainingWork(PhorgeWorkerBulkJob $job) {
    return (bool)queryfx_one(
      $job->establishConnection('r'),
      'SELECT * FROM %T WHERE bulkJobPHID = %s
        AND status NOT IN (%Ls) LIMIT 1',
      id(new PhorgeWorkerBulkTask())->getTableName(),
      $job->getPHID(),
      array(
        PhorgeWorkerBulkTask::STATUS_DONE,
        PhorgeWorkerBulkTask::STATUS_FAIL,
      ));
  }

  protected function updateJobStatus(PhorgeWorkerBulkJob $job, $status) {
    $type_status = PhorgeWorkerBulkJobTransaction::TYPE_STATUS;

    $xactions = array();
    $xactions[] = id(new PhorgeWorkerBulkJobTransaction())
      ->setTransactionType($type_status)
      ->setNewValue($status);

    $daemon_source = $this->newContentSource();

    $app_phid = id(new PhorgeDaemonsApplication())->getPHID();

    $editor = id(new PhorgeWorkerBulkJobEditor())
      ->setActor(PhorgeUser::getOmnipotentUser())
      ->setActingAsPHID($app_phid)
      ->setContentSource($daemon_source)
      ->setContinueOnMissingFields(true)
      ->applyTransactions($job, $xactions);
  }

}
