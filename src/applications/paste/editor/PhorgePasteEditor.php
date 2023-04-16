<?php

final class PhorgePasteEditor
  extends PhorgeApplicationTransactionEditor {

  private $newPasteTitle;

  public function getNewPasteTitle() {
    return $this->newPasteTitle;
  }

  public function getEditorApplicationClass() {
    return 'PhorgePasteApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Pastes');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this paste.', $author);
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

  protected function expandTransactions(
    PhorgeLiskDAO $object,
    array $xactions) {

    $new_title = $object->getTitle();
    foreach ($xactions as $xaction) {
      $type = $xaction->getTransactionType();
      if ($type === PhorgePasteTitleTransaction::TRANSACTIONTYPE) {
        $new_title = $xaction->getNewValue();
      }
    }
    $this->newPasteTitle = $new_title;

    return parent::expandTransactions($object, $xactions);
  }

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {

    if ($this->getIsNewObject()) {
      return false;
    }

    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Paste]');
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getAuthorPHID(),
      $this->getActingAsPHID(),
    );
  }

  public function getMailTagsMap() {
    return array(
      PhorgePasteTransaction::MAILTAG_CONTENT =>
        pht('Paste title, language or text changes.'),
      PhorgePasteTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a paste.'),
      PhorgePasteTransaction::MAILTAG_OTHER =>
        pht('Other paste activity not listed above occurs.'),
    );
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PasteReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $name = $object->getTitle();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("P{$id}: {$name}");
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('PASTE DETAIL'),
      PhorgeEnv::getProductionURI('/P'.$object->getID()));

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
