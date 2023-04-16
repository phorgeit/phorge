<?php

final class PhorgeStringMailStamp
  extends PhorgeMailStamp {

  const STAMPTYPE = 'string';

  public function renderStamps($value) {
    if ($value === null || $value === '') {
      return null;
    }

    $value = (array)$value;
    if (!$value) {
      return null;
    }

    $results = array();
    foreach ($value as $v) {
      $results[] = $this->renderStamp($this->getKey(), $v);
    }

    return $results;
  }

}
