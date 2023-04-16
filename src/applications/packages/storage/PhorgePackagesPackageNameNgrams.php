<?php

final class PhorgePackagesPackageNameNgrams
  extends PhorgePackagesNgrams {

  public function getNgramKey() {
    return 'packagename';
  }

  public function getColumnName() {
    return 'name';
  }

}
