<?php

final class PonderQuestionEditor
  extends PonderEditor {

  private $answer;

  public function getEditorObjectsDescription() {
    return pht('Ponder Questions');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s asked this question.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s asked %s.', $author, $object);
  }

  /**
   * This is used internally on @{method:applyInitialEffects} if a transaction
   * of type PonderQuestionTransaction::TYPE_ANSWERS is in the mix. The value
   * is set to the //last// answer in the transactions. Practically, one
   * answer is given at a time in the application, though theoretically
   * this is buggy.
   *
   * The answer is used in emails to generate proper links.
   */
  private function setAnswer(PonderAnswer $answer) {
    $this->answer = $answer;
    return $this;
  }
  private function getAnswer() {
    return $this->answer;
  }

  protected function shouldApplyInitialEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PonderQuestionAnswerTransaction::TRANSACTIONTYPE:
          return true;
      }
    }

    return false;
  }

  protected function applyInitialEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case PonderQuestionAnswerTransaction::TRANSACTIONTYPE:
          $new_value = $xaction->getNewValue();
          $new = idx($new_value, '+', array());
          foreach ($new as $new_answer) {
            $answer = idx($new_answer, 'answer');
            if (!$answer) {
              continue;
            }
            $answer->save();
            $this->setAnswer($answer);
          }
          break;
      }
    }
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;

    return $types;
  }

  protected function supportsSearch() {
    return true;
  }

  protected function shouldImplyCC(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PonderQuestionAnswerTransaction::TRANSACTIONTYPE:
        return false;
    }

    return parent::shouldImplyCC($object, $xaction);
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
      foreach ($xactions as $xaction) {
        switch ($xaction->getTransactionType()) {
          case PonderQuestionAnswerTransaction::TRANSACTIONTYPE:
            return false;
        }
      }
      return true;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getAuthorPHID(),
      $this->requireActor()->getPHID(),
    );
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
      foreach ($xactions as $xaction) {
        switch ($xaction->getTransactionType()) {
          case PonderQuestionAnswerTransaction::TRANSACTIONTYPE:
            return false;
        }
      }
      return true;
  }

  public function getMailTagsMap() {
    return array(
      PonderQuestionTransaction::MAILTAG_DETAILS =>
        pht('Someone changes the questions details.'),
      PonderQuestionTransaction::MAILTAG_ANSWERS =>
        pht('Someone adds a new answer.'),
      PonderQuestionTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on the question.'),
      PonderQuestionTransaction::MAILTAG_OTHER =>
        pht('Other question activity not listed above occurs.'),
    );
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PonderQuestionReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $title = $object->getTitle();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("Q{$id}: {$title}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $header = pht('QUESTION DETAIL');
    $uri = '/Q'.$object->getID();
    foreach ($xactions as $xaction) {
      $type = $xaction->getTransactionType();
      $old = $xaction->getOldValue();
      $new = $xaction->getNewValue();
      // If the user just asked the question, add the question text.
      if ($type == PonderQuestionContentTransaction::TRANSACTIONTYPE) {
        if ($old === null) {
          $body->addRawSection($new);
        }
      }
    }

    $body->addLinkSection(
      $header,
      PhorgeEnv::getProductionURI($uri));

    return $body;
  }

  protected function shouldApplyHeraldRules(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhorgeLiskDAO $object,
    array $xactions) {

    return id(new HeraldPonderQuestionAdapter())
      ->setQuestion($object);
  }

}
