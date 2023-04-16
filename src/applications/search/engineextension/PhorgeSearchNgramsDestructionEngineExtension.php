<?php

final class PhorgeSearchNgramsDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'search.ngrams';

  public function getExtensionName() {
    return pht('Search Ngram');
  }

  public function canDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return ($object instanceof PhorgeNgramsInterface);
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    foreach ($object->newNgrams() as $ngram) {
      queryfx(
        $ngram->establishConnection('w'),
        'DELETE FROM %T WHERE objectID = %d',
        $ngram->getTableName(),
        $object->getID());
    }
  }

}
