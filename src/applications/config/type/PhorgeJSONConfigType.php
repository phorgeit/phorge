<?php

abstract class PhorgeJSONConfigType
  extends PhorgeTextConfigType {

  protected function newCanonicalValue(
    PhorgeConfigOption $option,
    $value) {

    try {
      $value = phutil_json_decode($value);
    } catch (Exception $ex) {
      throw $this->newException(
        pht(
          'Value for option "%s" (of type "%s") must be specified in JSON, '.
          'but input could not be decoded: %s',
          $option->getKey(),
          $this->getTypeKey(),
          $ex->getMessage()));
    }

    return $value;
  }

  protected function newControl(PhorgeConfigOption $option) {
    return id(new AphrontFormTextAreaControl())
      ->setHeight(AphrontFormTextAreaControl::HEIGHT_VERY_TALL)
      ->setCustomClass('PhorgeMonospaced')
      ->setCaption(pht('Enter value in JSON.'));
  }

  public function newDisplayValue(
    PhorgeConfigOption $option,
    $value) {
    return PhorgeConfigJSON::prettyPrintJSON($value);
  }

}
