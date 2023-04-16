<?php

final class AlmanacDeviceNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'devicename';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'almanac';
  }

}
