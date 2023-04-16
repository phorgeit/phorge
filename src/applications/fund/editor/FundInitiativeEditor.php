<?php

final class FundInitiativeEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeFundApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Fund Initiatives');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this initiative.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
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
      FundInitiativeTransaction::MAILTAG_BACKER =>
        pht('Someone backs an initiative.'),
      FundInitiativeTransaction::MAILTAG_STATUS =>
        pht("An initiative's status changes."),
      FundInitiativeTransaction::MAILTAG_OTHER =>
        pht('Other initiative activity not listed above occurs.'),
    );
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $monogram = $object->getMonogram();
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("{$monogram}: {$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('INITIATIVE DETAIL'),
      PhorgeEnv::getProductionURI('/'.$object->getMonogram()));

    return $body;
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array($object->getOwnerPHID());
  }

  protected function getMailSubjectPrefix() {
    return 'Fund';
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new FundInitiativeReplyHandler())
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
