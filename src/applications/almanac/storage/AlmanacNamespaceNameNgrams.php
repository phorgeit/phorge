<?php

final class AlmanacNamespaceNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'namespacename';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'almanac';
  }

}
