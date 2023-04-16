<?php

abstract class PhorgeConfigOptionType extends Phobject {

  public function validateOption(PhorgeConfigOption $option, $value) {
    return;
  }

  public function readRequest(
    PhorgeConfigOption $option,
    AphrontRequest $request) {

    $e_value = null;
    $errors = array();
    $storage_value = $request->getStr('value');
    $display_value = $request->getStr('value');

    return array($e_value, $errors, $storage_value, $display_value);
  }

  public function getDisplayValue(
    PhorgeConfigOption $option,
    PhorgeConfigEntry $entry,
    $value) {

    if (is_array($value)) {
      return PhorgeConfigJSON::prettyPrintJSON($value);
    } else {
      return $value;
    }

  }

  public function renderControls(
    PhorgeConfigOption $option,
    $display_value,
    $e_value) {

    $control = $this->renderControl($option, $display_value, $e_value);

    return array($control);
  }

  public function renderControl(
    PhorgeConfigOption $option,
    $display_value,
    $e_value) {

    return id(new AphrontFormTextControl())
      ->setName('value')
      ->setLabel(pht('Value'))
      ->setValue($display_value)
      ->setError($e_value);
  }

}
