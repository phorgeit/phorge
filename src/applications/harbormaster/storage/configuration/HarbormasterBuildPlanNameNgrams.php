<?php

final class HarbormasterBuildPlanNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'buildplanname';
  }

  public function getColumnName() {
    return 'name';
  }

  public function getApplicationName() {
    return 'harbormaster';
  }

}
