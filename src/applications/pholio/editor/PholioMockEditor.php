<?php

final class PholioMockEditor extends PhorgeApplicationTransactionEditor {

  private $images = array();

  public function getEditorApplicationClass() {
    return 'PhorgePholioApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Pholio Mocks');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this mock.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDGE;
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

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PholioReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $monogram = $object->getMonogram();
    $name = $object->getName();

    return id(new PhorgeMetaMTAMail())
      ->setSubject("{$monogram}: {$name}");
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array(
      $object->getAuthorPHID(),
      $this->requireActor()->getPHID(),
    );
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $viewer = $this->requireActor();

    $body = id(new PhorgeMetaMTAMailBody())
      ->setViewer($viewer);

    $mock_uri = $object->getURI();
    $mock_uri = PhorgeEnv::getProductionURI($mock_uri);

    $this->addHeadersAndCommentsToMailBody(
      $body,
      $xactions,
      pht('View Mock'),
      $mock_uri);

    $type_inline = PholioMockInlineTransaction::TRANSACTIONTYPE;

    $inlines = array();
    foreach ($xactions as $xaction) {
      if ($xaction->getTransactionType() == $type_inline) {
        $inlines[] = $xaction;
      }
    }

    $this->appendInlineCommentsForMail($object, $inlines, $body);

    $body->addLinkSection(
      pht('MOCK DETAIL'),
      PhorgeEnv::getProductionURI($object->getURI()));

    return $body;
  }

  private function appendInlineCommentsForMail(
    $object,
    array $inlines,
    PhorgeMetaMTAMailBody $body) {

    if (!$inlines) {
      return;
    }

    $viewer = $this->requireActor();

    $header = pht('INLINE COMMENTS');
    $body->addRawPlaintextSection($header);
    $body->addRawHTMLSection(phutil_tag('strong', array(), $header));

    $image_ids = array();
    foreach ($inlines as $inline) {
      $comment = $inline->getComment();
      $image_id = $comment->getImageID();
      $image_ids[$image_id] = $image_id;
    }

    $images = id(new PholioImageQuery())
      ->setViewer($viewer)
      ->withIDs($image_ids)
      ->execute();
    $images = mpull($images, null, 'getID');

    foreach ($inlines as $inline) {
      $comment = $inline->getComment();
      $content = $comment->getContent();
      $image_id = $comment->getImageID();
      $image = idx($images, $image_id);
      if ($image) {
        $image_name = $image->getName();
      } else {
        $image_name = pht('Unknown (ID %d)', $image_id);
      }

      $body->addRemarkupSection(
        pht('Image "%s":', $image_name),
        $content);
    }
  }

  protected function getMailSubjectPrefix() {
    return pht('[Pholio]');
  }

  public function getMailTagsMap() {
    return array(
      PholioTransaction::MAILTAG_STATUS =>
        pht("A mock's status changes."),
      PholioTransaction::MAILTAG_COMMENT =>
        pht('Someone comments on a mock.'),
      PholioTransaction::MAILTAG_UPDATED =>
        pht('Mock images or descriptions change.'),
      PholioTransaction::MAILTAG_OTHER =>
        pht('Other mock activity not listed above occurs.'),
    );
  }

  protected function shouldPublishFeedStory(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function supportsSearch() {
    return true;
  }

  protected function shouldApplyHeraldRules(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function buildHeraldAdapter(
    PhorgeLiskDAO $object,
    array $xactions) {

    return id(new HeraldPholioMockAdapter())
      ->setMock($object);
  }

  protected function sortTransactions(array $xactions) {
    $head = array();
    $tail = array();

    // Move inline comments to the end, so the comments precede them.
    foreach ($xactions as $xaction) {
      $type = $xaction->getTransactionType();
      if ($type == PholioMockInlineTransaction::TRANSACTIONTYPE) {
        $tail[] = $xaction;
      } else {
        $head[] = $xaction;
      }
    }

    return array_values(array_merge($head, $tail));
  }

  protected function shouldImplyCC(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PholioMockInlineTransaction::TRANSACTIONTYPE:
        return true;
    }

    return parent::shouldImplyCC($object, $xaction);
  }

  public function loadPholioImage($object, $phid) {
    if (!isset($this->images[$phid])) {

      $image = id(new PholioImageQuery())
        ->setViewer($this->getActor())
        ->withPHIDs(array($phid))
        ->executeOne();

      if (!$image) {
        throw new Exception(
          pht(
            'No image exists with PHID "%s".',
            $phid));
      }

      $mock_phid = $image->getMockPHID();
      if ($mock_phid) {
        if ($mock_phid !== $object->getPHID()) {
          throw new Exception(
            pht(
              'Image ("%s") belongs to the wrong object ("%s", expected "%s").',
              $phid,
              $mock_phid,
              $object->getPHID()));
        }
      }

      $this->images[$phid] = $image;
    }

    return $this->images[$phid];
  }

}
