<?php

final class PhorgeFileNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'filename';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'file';
  }

}
