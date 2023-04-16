<?php

final class DrydockBlueprintNameNgrams
  extends PhorgeSearchNgrams {

  public function getNgramKey() {
    return 'blueprintname';
  }

  public function getColumnName() {
    return 'blueprintName';
  }

  public function getApplicationName() {
    return 'drydock';
  }

}
