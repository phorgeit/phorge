<?php

abstract class PhorgeListExportField
  extends PhorgeExportField {

  public function getTextValue($value) {
    return implode("\n", $value);
  }

}
