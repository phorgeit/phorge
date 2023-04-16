<?php

final class PhorgeIntConfigType
  extends PhorgeTextConfigType {

  const TYPEKEY = 'int';

  protected function newCanonicalValue(
    PhorgeConfigOption $option,
    $value) {

    if (!preg_match('/^-?[0-9]+\z/', $value)) {
      throw $this->newException(
        pht(
          'Value for option "%s" must be an integer.',
          $option->getKey()));
    }

    return (int)$value;
  }

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {

    if (!is_int($value)) {
      throw $this->newException(
        pht(
          'Option "%s" is of type "%s", but the configured value is not '.
          'an integer.',
          $option->getKey(),
          $this->getTypeKey()));
    }
  }

}
