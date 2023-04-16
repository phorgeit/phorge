<?php

final class LegalpadDocumentEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeLegalpadApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Legalpad Documents');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_COMMENT;
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this document.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    $is_contribution = false;

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case LegalpadDocumentTitleTransaction::TRANSACTIONTYPE:
        case LegalpadDocumentTextTransaction::TRANSACTIONTYPE:
          $is_contribution = true;
          break;
      }
    }

    if ($is_contribution) {
      $text = $object->getDocumentBody()->getText();
      $title = $object->getDocumentBody()->getTitle();
      $object->setVersions($object->getVersions() + 1);

      $body = new LegalpadDocumentBody();
      $body->setCreatorPHID($this->getActingAsPHID());
      $body->setText($text);
      $body->setTitle($title);
      $body->setVersion($object->getVersions());
      $body->setDocumentPHID($object->getPHID());
      $body->save();

      $object->setDocumentBodyPHID($body->getPHID());

      $type = PhorgeContributedToObjectEdgeType::EDGECONST;
      id(new PhorgeEdgeEditor())
        ->addEdge($this->getActingAsPHID(), $type, $object->getPHID())
        ->save();

      $type = PhorgeObjectHasContributorEdgeType::EDGECONST;
      $contributors = PhorgeEdgeQuery::loadDestinationPHIDs(
        $object->getPHID(),
        $type);
      $object->setRecentContributorPHIDs(array_slice($contributors, 0, 3));
      $object->setContributorCount(count($contributors));

      $object->save();
    }

    return $xactions;
  }

  protected function validateAllTransactions(PhorgeLiskDAO $object,
    array $xactions) {
    $errors = array();

    $is_required = (bool)$object->getRequireSignature();
    $document_type = $object->getSignatureType();
    $individual = LegalpadDocument::SIGNATURE_TYPE_INDIVIDUAL;

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case LegalpadDocumentRequireSignatureTransaction::TRANSACTIONTYPE:
          $is_required = (bool)$xaction->getNewValue();
          break;
        case LegalpadDocumentSignatureTypeTransaction::TRANSACTIONTYPE:
          $document_type = $xaction->getNewValue();
          break;
      }
    }

    if ($is_required && ($document_type != $individual)) {
      $errors[] = new PhorgeApplicationTransactionValidationError(
        LegalpadDocumentRequireSignatureTransaction::TRANSACTIONTYPE,
        pht('Invalid'),
        pht('Only documents with signature type "individual" may '.
            'require signing to log in.'),
        null);
    }

    return $errors;
  }


/* -(  Sending Mail  )------------------------------------------------------- */

  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new LegalpadReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $title = $object->getDocumentBody()->getTitle();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("L{$id}: {$title}");
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getCreatorPHID(),
      $this->requireActor()->getPHID(),
    );
  }

  protected function shouldImplyCC(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case LegalpadDocumentTextTransaction::TRANSACTIONTYPE:
      case LegalpadDocumentTitleTransaction::TRANSACTIONTYPE:
      case LegalpadDocumentPreambleTransaction::TRANSACTIONTYPE:
      case LegalpadDocumentRequireSignatureTransaction::TRANSACTIONTYPE:
        return true;
    }

    return parent::shouldImplyCC($object, $xaction);
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    $body->addLinkSection(
      pht('DOCUMENT DETAIL'),
      PhorgeEnv::getProductionURI('/legalpad/view/'.$object->getID().'/'));

    return $body;
  }

  protected function getMailSubjectPrefix() {
    return pht('[Legalpad]');
  }


  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return false;
  }

  protected function supportsSearch() {
    return false;
  }

}
