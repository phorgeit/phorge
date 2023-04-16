<?php

final class AlmanacServiceNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'servicename';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'almanac';
  }

}
