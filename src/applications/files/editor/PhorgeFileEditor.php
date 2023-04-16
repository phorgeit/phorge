<?php

final class PhorgeFileEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeFilesApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Files');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[File]');
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getAuthorPHID(),
      $this->requireActor()->getPHID(),
    );
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new FileReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("F{$id}: {$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addTextSection(
      pht('FILE DETAIL'),
      PhorgeEnv::getProductionURI($object->getInfoURI()));

    return $body;
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
