<?php

abstract class PhorgeTextConfigType
  extends PhorgeConfigType {

  public function isValuePresentInRequest(
    PhorgeConfigOption $option,
    AphrontRequest $request) {
    $value = parent::readValueFromRequest($option, $request);
    return (bool)strlen($value);
  }

  protected function newCanonicalValue(
    PhorgeConfigOption $option,
    $value) {
    return (string)$value;
  }

  protected function newHTTPParameterType() {
    return new AphrontStringHTTPParameterType();
  }

  protected function newControl(PhorgeConfigOption $option) {
    return new AphrontFormTextControl();
  }

}
