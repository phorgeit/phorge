<?php

final class PhabricatorIDExportField
  extends PhabricatorExportField {

  /**
   * @return int
   */
  public function getNaturalValue($value) {
    return (int)$value;
  }

  public function getCharacterWidth() {
    return 12;
  }

}
