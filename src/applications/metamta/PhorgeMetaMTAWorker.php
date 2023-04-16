<?php

final class PhorgeMetaMTAWorker
  extends PhorgeWorker {

  public function getMaximumRetryCount() {
    return 250;
  }

  public function getWaitBeforeRetry(PhorgeWorkerTask $task) {
    return ($task->getFailureCount() * 15);
  }

  protected function doWork() {
    $message = $this->loadMessage();

    if ($message->getStatus() != PhorgeMailOutboundStatus::STATUS_QUEUE) {
      return;
    }

    try {
      $message->sendNow();
    } catch (PhorgeMetaMTAPermanentFailureException $ex) {
      // If the mailer fails permanently, fail this task permanently.
      throw new PhorgeWorkerPermanentFailureException($ex->getMessage());
    }
  }

  private function loadMessage() {
    $message_id = $this->getTaskData();
    $message = id(new PhorgeMetaMTAMail())
      ->load($message_id);

    if (!$message) {
      throw new PhorgeWorkerPermanentFailureException(
        pht(
          'Unable to load mail message (with ID "%s") while preparing to '.
          'deliver it.',
          $message_id));
    }

    return $message;
  }

  public function renderForDisplay(PhorgeUser $viewer) {
    return phutil_tag(
      'pre',
      array(
      ),
      'phorge/ $ ./bin/mail show-outbound --id '.$this->getTaskData());
  }

}
