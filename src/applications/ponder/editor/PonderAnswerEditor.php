<?php

final class PonderAnswerEditor extends PonderEditor {

  public function getEditorObjectsDescription() {
    return pht('Ponder Answers');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s added this answer.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s added %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_COMMENT;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    $phids = array();
    $phids[] = $object->getAuthorPHID();
    $phids[] = $this->requireActor()->getPHID();

    $question = id(new PonderQuestionQuery())
      ->setViewer($this->requireActor())
      ->withIDs(array($object->getQuestionID()))
      ->executeOne();

    $phids[] = $question->getAuthorPHID();

    return $phids;
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
      return true;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PonderAnswerReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("ANSR{$id}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    // If the user just gave the answer, add the answer text.
    foreach ($xactions as $xaction) {
      $type = $xaction->getTransactionType();
      $new = $xaction->getNewValue();
      if ($type == PonderAnswerContentTransaction::TRANSACTIONTYPE) {
        $body->addRawSection($new);
      }
    }

    $body->addLinkSection(
      pht('ANSWER DETAIL'),
      PhorgeEnv::getProductionURI($object->getURI()));

    return $body;
  }

}
