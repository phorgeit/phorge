<?php

final class PhorgeBoolConfigType
  extends PhorgeTextConfigType {

  const TYPEKEY = 'bool';

  protected function newCanonicalValue(
    PhorgeConfigOption $option,
    $value) {

    if (!preg_match('/^(true|false)\z/', $value)) {
      throw $this->newException(
        pht(
          'Value for option "%s" of type "%s" must be either '.
          '"true" or "false".',
          $option->getKey(),
          $this->getTypeKey()));
    }

    return ($value === 'true');
  }

  public function newDisplayValue(
    PhorgeConfigOption $option,
    $value) {

    if ($value) {
      return 'true';
    } else {
      return 'false';
    }
  }

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {

    if (!is_bool($value)) {
      throw $this->newException(
        pht(
          'Option "%s" is of type "%s", but the configured value is not '.
          'a boolean.',
          $option->getKey(),
          $this->getTypeKey()));
    }
  }

  protected function newControl(PhorgeConfigOption $option) {
    $bool_map = $option->getBoolOptions();

    $map = array(
      '' => pht('(Use Default)'),
    ) + array(
      'true'  => idx($bool_map, 0),
      'false' => idx($bool_map, 1),
    );

    return id(new AphrontFormSelectControl())
      ->setOptions($map);
  }
}
