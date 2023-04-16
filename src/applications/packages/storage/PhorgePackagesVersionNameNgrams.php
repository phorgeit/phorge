<?php

final class PhorgePackagesVersionNameNgrams
  extends PhorgePackagesNgrams {

  public function getNgramKey() {
    return 'versionname';
  }

  public function getColumnName() {
    return 'name';
  }

}
