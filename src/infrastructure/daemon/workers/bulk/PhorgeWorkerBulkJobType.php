<?php

abstract class PhorgeWorkerBulkJobType extends Phobject {

  abstract public function getJobName(PhorgeWorkerBulkJob $job);
  abstract public function getBulkJobTypeKey();
  abstract public function getJobSize(PhorgeWorkerBulkJob $job);
  abstract public function getDescriptionForConfirm(
    PhorgeWorkerBulkJob $job);

  abstract public function createTasks(PhorgeWorkerBulkJob $job);
  abstract public function runTask(
    PhorgeUser $actor,
    PhorgeWorkerBulkJob $job,
    PhorgeWorkerBulkTask $task);

  public function getDoneURI(PhorgeWorkerBulkJob $job) {
    return $job->getManageURI();
  }

  final public static function getAllJobTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getBulkJobTypeKey')
      ->execute();
  }

  public function getCurtainActions(
    PhorgeUser $viewer,
    PhorgeWorkerBulkJob $job) {

    if ($job->isConfirming()) {
      $continue_uri = $job->getMonitorURI();
    } else {
      $continue_uri = $job->getDoneURI();
    }

    $continue = id(new PhorgeActionView())
      ->setHref($continue_uri)
      ->setIcon('fa-arrow-circle-o-right')
      ->setName(pht('Continue'));

    return array(
      $continue,
    );
  }

}
