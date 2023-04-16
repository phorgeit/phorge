<?php

final class PhorgePhurlURLEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhurlApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phurl');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this URL.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function supportsSearch() {
    return true;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  public function getMailTagsMap() {
    return array(
      PhorgePhurlURLTransaction::MAILTAG_DETAILS =>
        pht(
          "A URL's details change."),
    );
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Phurl]');
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    $phids = array();
    $phids[] = $this->getActingAsPHID();

    return $phids;
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("U{$id}: {$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $description = $object->getDescription();
    $body = parent::buildMailBody($object, $xactions);

    if (strlen($description)) {
      $body->addRemarkupSection(
        pht('URL DESCRIPTION'),
        $object->getDescription());
    }

    $body->addLinkSection(
      pht('URL DETAIL'),
      PhorgeEnv::getProductionURI('/U'.$object->getID()));


    return $body;
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();
    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhorgePhurlURLAliasTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht('This alias is already in use.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgePhurlURLReplyHandler())
      ->setMailReceiver($object);
  }

}
