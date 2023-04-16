<?php

final class PhorgeLiskFulltextEngineExtension
  extends PhorgeFulltextEngineExtension {

  const EXTENSIONKEY = 'lisk';

  public function getExtensionName() {
    return pht('Lisk Builtin Properties');
  }

  public function shouldEnrichFulltextObject($object) {
    if (!($object instanceof PhorgeLiskDAO)) {
      return false;
    }

    if (!$object->getConfigOption(LiskDAO::CONFIG_TIMESTAMPS)) {
      return false;
    }

    return true;
  }

  public function enrichFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {

    $document
      ->setDocumentCreated($object->getDateCreated())
      ->setDocumentModified($object->getDateModified());

  }

}
