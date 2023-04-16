<?php

final class PhorgeCustomFieldFulltextEngineExtension
  extends PhorgeFulltextEngineExtension {

  const EXTENSIONKEY = 'customfield.fields';

  public function getExtensionName() {
    return pht('Custom Fields');
  }

  public function shouldEnrichFulltextObject($object) {
    return ($object instanceof PhorgeCustomFieldInterface);
  }

  public function enrichFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {

    // Rebuild the ApplicationSearch indexes. These are internal and not part
    // of the fulltext search, but putting them in this workflow allows users
    // to use the same tools to rebuild the indexes, which is easy to
    // understand.

    $field_list = PhorgeCustomField::getObjectFields(
      $object,
      PhorgeCustomField::ROLE_DEFAULT);

    $field_list->setViewer($this->getViewer());
    $field_list->readFieldsFromStorage($object);

    // Rebuild ApplicationSearch indexes.
    $field_list->rebuildIndexes($object);

    // Rebuild global search indexes.
    $field_list->updateAbstractDocument($document);
  }

}
