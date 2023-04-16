<?php

final class PhorgeFileRawStorageFormat
  extends PhorgeFileStorageFormat {

  const FORMATKEY = 'raw';

  public function getStorageFormatName() {
    return pht('Raw Data');
  }

  public function newReadIterator($raw_iterator) {
    return $raw_iterator;
  }

  public function newWriteIterator($raw_iterator) {
    return $raw_iterator;
  }

}
