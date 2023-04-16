<?php

final class PhorgePhurlURLNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'phurlname';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'phurl';
  }

}
