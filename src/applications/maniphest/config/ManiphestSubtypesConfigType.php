<?php

final class ManiphestSubtypesConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'maniphest.subtypes';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    PhorgeEditEngineSubtype::validateConfiguration($value);
  }

}
