<?php

final class PhorgePackagesPublisherNameNgrams
  extends PhorgePackagesNgrams {

  public function getNgramKey() {
    return 'publishername';
  }

  public function getColumnName() {
    return 'name';
  }

}
