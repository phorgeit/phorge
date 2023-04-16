<?php

final class PhorgeSlowvoteEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeSlowvoteApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Slowvote');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this poll.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  public function getMailTagsMap() {
    return array(
      PhorgeSlowvoteTransaction::MAILTAG_DETAILS =>
        pht('Someone changes the poll details.'),
      PhorgeSlowvoteTransaction::MAILTAG_RESPONSES =>
        pht('Someone votes on a poll.'),
      PhorgeSlowvoteTransaction::MAILTAG_OTHER =>
        pht('Other poll activity not listed above occurs.'),
    );
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $monogram = $object->getMonogram();
    $name = $object->getQuestion();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("{$monogram}: {$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);
    $description = $object->getDescription();

    if (strlen($description)) {
      $body->addRemarkupSection(
        pht('SLOWVOTE DESCRIPTION'),
        $object->getDescription());
    }

    $body->addLinkSection(
      pht('SLOWVOTE DETAIL'),
      PhorgeEnv::getProductionURI('/'.$object->getMonogram()));

    return $body;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getAuthorPHID(),
      $this->requireActor()->getPHID(),
    );
  }
  protected function getMailSubjectPrefix() {
    return '[Slowvote]';
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgeSlowvoteReplyHandler())
      ->setMailReceiver($object);
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

}
