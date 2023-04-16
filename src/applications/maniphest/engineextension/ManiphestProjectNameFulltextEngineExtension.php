<?php

final class ManiphestProjectNameFulltextEngineExtension
  extends PhorgeFulltextEngineExtension {

  const EXTENSIONKEY = 'maniphest.project.name';

  public function getExtensionName() {
    return pht('Maniphest Project Name Cache');
  }

  public function shouldIndexFulltextObject($object) {
    return ($object instanceof PhorgeProject);
  }

  public function indexFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {

    ManiphestNameIndex::updateIndex(
      $object->getPHID(),
      $object->getName());
  }

}
