<?php

final class ManiphestCreateMailReceiver
  extends PhorgeApplicationMailReceiver {

  protected function newApplication() {
    return new PhorgeManiphestApplication();
  }

  protected function processReceivedMail(
    PhorgeMetaMTAReceivedMail $mail,
    PhutilEmailAddress $target) {

    $author = $this->getAuthor();
    $task = ManiphestTask::initializeNewTask($author);

    $from_address = $mail->newFromAddress();
    if ($from_address) {
      $task->setOriginalEmailSource((string)$from_address);
    }

    $handler = new ManiphestReplyHandler();
    $handler->setMailReceiver($task);

    $handler->setActor($author);
    $handler->setExcludeMailRecipientPHIDs(
      $mail->loadAllRecipientPHIDs());
    if ($this->getApplicationEmail()) {
      $handler->setApplicationEmail($this->getApplicationEmail());
    }
    $handler->processEmail($mail);

    $mail->setRelatedPHID($task->getPHID());
  }

}
