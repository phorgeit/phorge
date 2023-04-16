<?php

final class PhorgeBoolMailStamp
  extends PhorgeMailStamp {

  const STAMPTYPE = 'bool';

  public function renderStamps($value) {
    if (!$value) {
      return null;
    }

    return $this->renderStamp($this->getKey());
  }

}
