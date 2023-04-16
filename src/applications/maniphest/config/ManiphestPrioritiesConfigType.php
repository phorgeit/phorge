<?php

final class ManiphestPrioritiesConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'maniphest.priorities';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    ManiphestTaskPriority::validateConfiguration($value);
  }

}
