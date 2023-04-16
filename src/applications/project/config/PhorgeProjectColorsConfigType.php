<?php

final class PhorgeProjectColorsConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'project.colors';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    PhorgeProjectIconSet::validateColorConfiguration($value);
  }

}
