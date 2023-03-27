<?php

final class PonderQuestionStatusTestCase extends PhutilTestCase {

  public function testClosedStatuses() {

    $statuses = PonderQuestionStatus::getQuestionStatusClosedMap();
    foreach ($statuses as $status) {
      $question = new PonderQuestion();
      $question->setStatus($status);
      $this->assertEqual(true, $question->isStatusClosed());
    }

  }

  public function testOpenedStatuses() {
    $statuses = PonderQuestionStatus::getQuestionStatusOpenMap();
    foreach ($statuses as $status) {
      $question = new PonderQuestion();
      $question->setStatus($status);
      $this->assertEqual(false, $question->isStatusClosed());
    }
  }

}
