<?php

final class PhorgeOwnersPackageNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'name';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'owners';
  }

}
