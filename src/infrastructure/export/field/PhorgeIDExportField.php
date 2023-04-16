<?php

final class PhorgeIDExportField
  extends PhorgeExportField {

  public function getNaturalValue($value) {
    return (int)$value;
  }

  public function getCharacterWidth() {
    return 12;
  }

}
