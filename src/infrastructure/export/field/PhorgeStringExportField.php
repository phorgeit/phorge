<?php

final class PhorgeStringExportField
  extends PhorgeExportField {

  public function getNaturalValue($value) {
    if ($value === null) {
      return $value;
    }

    if (!strlen($value)) {
      return null;
    }

    return (string)$value;
  }

}
