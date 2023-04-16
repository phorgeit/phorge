<?php

final class PhorgeProjectSubtypesConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'projects.subtypes';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    PhorgeEditEngineSubtype::validateConfiguration($value);
  }

}
