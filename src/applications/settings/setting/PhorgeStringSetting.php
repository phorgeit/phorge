<?php

abstract class PhorgeStringSetting
  extends PhorgeSetting {

  final protected function newCustomEditField($object) {
    return $this->newEditField($object, new PhorgeTextEditField());
  }

  public function getTransactionNewValue($value) {
    if (!strlen($value)) {
      return null;
    }

    return (string)$value;
  }

}
