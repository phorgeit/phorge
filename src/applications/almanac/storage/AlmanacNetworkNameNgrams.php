<?php

final class AlmanacNetworkNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'networkname';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'almanac';
  }

}
