<?php

final class PhorgeClassConfigType
  extends PhorgeTextConfigType {

  const TYPEKEY = 'class';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {

    if (!is_string($value)) {
      throw $this->newException(
        pht(
          'Option "%s" is of type "%s", but the configured value is not '.
          'a string.',
          $option->getKey(),
          $this->getTypeKey()));
    }

    $base = $option->getBaseClass();
    $map = $this->getClassOptions($option);

    try {
      $ok = class_exists($value);
    } catch (Exception $ex) {
      $ok = false;
    }

    if (!$ok) {
      throw $this->newException(
        pht(
          'Option "%s" is of type "%s", but the configured value is not the '.
          'name of a known class. Valid selections are: %s.',
          $option->getKey(),
          $this->getTypeKey(),
          implode(', ', array_keys($map))));
    }

    if (!isset($map[$value])) {
      throw $this->newException(
        pht(
          'Option "%s" is of type "%s", but the current value ("%s") is not '.
          'a known, concrete subclass of base class "%s". Valid selections '.
          'are: %s.',
          $option->getKey(),
          $this->getTypeKey(),
          $value,
          $base,
          implode(', ', array_keys($map))));
    }
  }

  protected function newControl(PhorgeConfigOption $option) {
    $map = array(
      '' => pht('(Use Default)'),
    ) + $this->getClassOptions($option);

    return id(new AphrontFormSelectControl())
      ->setOptions($map);
  }

  private function getClassOptions(PhorgeConfigOption $option) {
    $symbols = id(new PhutilSymbolLoader())
      ->setType('class')
      ->setAncestorClass($option->getBaseClass())
      ->setConcreteOnly(true)
      ->selectSymbolsWithoutLoading();

    $map = ipull($symbols, 'name', 'name');
    asort($map);

    return $map;
  }

}
