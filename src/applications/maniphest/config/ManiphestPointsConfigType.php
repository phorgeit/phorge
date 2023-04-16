<?php

final class ManiphestPointsConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'maniphest.points';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    ManiphestTaskPoints::validateConfiguration($value);
  }

}
