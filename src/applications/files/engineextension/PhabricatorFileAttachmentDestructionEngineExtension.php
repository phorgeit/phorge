<?php

final class PhabricatorFileAttachmentDestructionEngineExtension
  extends PhabricatorDestructionEngineExtension {

  const EXTENSIONKEY = 'file.attachments';

  public function getExtensionName() {
    return pht('File Attachments');
  }

  public function destroyObject(
    PhabricatorDestructionEngine $engine,
    $object) {
    $attachments = id(new PhabricatorFileAttachment())->loadAllWhere(
      'objectPHID = %s',
      $object->getPHID());
    foreach ($attachments as $attachment) {
      $attachment->delete();
    }
  }
}
