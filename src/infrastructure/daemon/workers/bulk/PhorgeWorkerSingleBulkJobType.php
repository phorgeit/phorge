<?php

/**
 * An bulk job which can not be parallelized and executes only one task.
 */
abstract class PhorgeWorkerSingleBulkJobType
  extends PhorgeWorkerBulkJobType {

  public function getDescriptionForConfirm(PhorgeWorkerBulkJob $job) {
    return null;
  }

  public function getJobSize(PhorgeWorkerBulkJob $job) {
    return 1;
  }

  public function createTasks(PhorgeWorkerBulkJob $job) {
    $tasks = array();

    $tasks[] = PhorgeWorkerBulkTask::initializeNewTask(
      $job,
      $job->getPHID());

    return $tasks;
  }

}
