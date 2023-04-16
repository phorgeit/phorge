<?php

final class PhorgeProjectIconsConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'project.icons';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    PhorgeProjectIconSet::validateConfiguration($value);
  }

}
