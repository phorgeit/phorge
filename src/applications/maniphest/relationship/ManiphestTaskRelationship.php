<?php

abstract class ManiphestTaskRelationship
  extends PhorgeObjectRelationship {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    $has_app = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeManiphestApplication',
      $viewer);
    if (!$has_app) {
      return false;
    }

    return ($object instanceof ManiphestTask);
  }

  protected function newMergeIntoTransactions(ManiphestTask $task) {
    return array(
      id(new ManiphestTransaction())
        ->setTransactionType(
          ManiphestTaskMergedIntoTransaction::TRANSACTIONTYPE)
        ->setNewValue($task->getPHID()),
    );
  }

  protected function newMergeFromTransactions(array $tasks) {
    $xactions = array();

    $subscriber_phids = $this->loadMergeSubscriberPHIDs($tasks);

    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(ManiphestTaskMergedFromTransaction::TRANSACTIONTYPE)
      ->setNewValue(mpull($tasks, 'getPHID'));

    $xactions[] = id(new ManiphestTransaction())
      ->setTransactionType(PhorgeTransactions::TYPE_SUBSCRIBERS)
      ->setNewValue(array('+' => $subscriber_phids));

    return $xactions;
  }

  private function loadMergeSubscriberPHIDs(array $tasks) {
    $phids = array();

    foreach ($tasks as $task) {
      $phids[] = $task->getAuthorPHID();
      $phids[] = $task->getOwnerPHID();
    }

    $subscribers = id(new PhorgeSubscribersQuery())
      ->withObjectPHIDs(mpull($tasks, 'getPHID'))
      ->execute();

    foreach ($subscribers as $phid => $subscriber_list) {
      foreach ($subscriber_list as $subscriber) {
        $phids[] = $subscriber;
      }
    }

    $phids = array_unique($phids);
    $phids = array_filter($phids);

    return $phids;
  }

}
