<?php

final class PhorgeLegalpadBodySearchEngineAttachment
  extends PhorgeSearchEngineAttachment {

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
