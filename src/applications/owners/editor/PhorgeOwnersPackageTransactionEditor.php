<?php

final class PhorgeOwnersPackageTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeOwnersApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Owners Packages');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Package]');
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $this->requireActor()->getPHID(),
    );
  }

  protected function getMailCC(PhorgeLiskDAO $object) {
    return mpull($object->getOwners(), 'getUserPHID');
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new OwnersPackageReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject($name);
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $detail_uri = PhorgeEnv::getProductionURI($object->getURI());

    $body->addLinkSection(
      pht('PACKAGE DETAIL'),
      $detail_uri);

    return $body;
  }

  protected function supportsSearch() {
    return true;
  }

}
