<?php

final class PhorgeBadgesBadgeNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'badgename';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'badges';
  }

}
