<?php

final class PhorgeNgramsIndexEngineExtension
  extends PhorgeIndexEngineExtension {

  const EXTENSIONKEY = 'ngrams';

  public function getExtensionName() {
    return pht('Ngrams Engine');
  }

  public function getIndexVersion($object) {
    $ngrams = $object->newNgrams();
    $map = mpull($ngrams, 'getValue', 'getNgramKey');
    ksort($map);
    $serialized = serialize($map);

    return PhorgeHash::digestForIndex($serialized);
  }

  public function shouldIndexObject($object) {
    return ($object instanceof PhorgeNgramsInterface);
  }

  public function indexObject(
    PhorgeIndexEngine $engine,
    $object) {

    foreach ($object->newNgrams() as $ngram) {
      $ngram->writeNgram($object->getID());
    }
  }

}
