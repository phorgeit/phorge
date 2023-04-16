<?php

final class PhabricatorLegalpadBodySearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Legalpad Document Body');
  }

  public function getAttachmentDescription() {
    return pht('Get the full content for each document.');
  }

  public function willLoadAttachmentData($query, $spec) {
    $query->needDocumentBodies(true);
  }

  public function getAttachmentForObject($object, $data, $spec) {
    return array(
      'body' => $object->getDocumentBody()->getText(),
      'preamble' => $object->getPreamble(),
    );
  }

}
