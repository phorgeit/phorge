<?php

final class ManiphestStatusesConfigType
  extends PhorgeJSONConfigType {

  const TYPEKEY = 'maniphest.statuses';

  public function validateStoredValue(
    PhorgeConfigOption $option,
    $value) {
    ManiphestTaskStatus::validateConfiguration($value);
  }

}
