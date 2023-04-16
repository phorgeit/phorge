<?php

final class PhorgeCountdownEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeCountdownApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Countdown');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDGE;
    $types[] = PhorgeTransactions::TYPE_SPACE;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;
    $types[] = PhorgeTransactions::TYPE_COMMENT;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  public function getMailTagsMap() {
    return array(
      PhorgeCountdownTransaction::MAILTAG_DETAILS =>
        pht('Someone changes the countdown details.'),
      PhorgeCountdownTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a countdown.'),
      PhorgeCountdownTransaction::MAILTAG_OTHER =>
        pht('Other countdown activity not listed above occurs.'),
    );
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $monogram = $object->getMonogram();
    $name = $object->getTitle();

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
        pht('COUNTDOWN DESCRIPTION'),
        $object->getDescription());
    }

    $body->addLinkSection(
      pht('COUNTDOWN DETAIL'),
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
    return '[Countdown]';
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgeCountdownReplyHandler())
      ->setMailReceiver($object);
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function supportsSearch() {
    return true;
  }

}
