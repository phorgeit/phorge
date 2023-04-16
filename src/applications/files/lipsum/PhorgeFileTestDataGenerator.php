<?php

final class PhorgeFileTestDataGenerator
  extends PhorgeTestDataGenerator {

  const GENERATORKEY = 'files';

  public function getGeneratorName() {
    return pht('Files');
  }

  public function generateObject() {
    $author_phid = $this->loadPhorgeUserPHID();
    $dimension = 1 << rand(5, 12);
    $image = id(new PhorgeLipsumMondrianArtist())
      ->generate($dimension, $dimension);
    $file = PhorgeFile::newFromFileData(
      $image,
      array(
        'name' => 'rand-'.rand(1000, 9999),
      ));
    $file->setAuthorPHID($author_phid);
    $file->setMimeType('image/jpeg');
    return $file->save();
  }
}
