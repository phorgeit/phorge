<?php

final class PhorgePDFFragmentOffset
  extends Phobject {

  private $fragment;
  private $offset;

  public function setFragment(PhorgePDFFragment $fragment) {
    $this->fragment = $fragment;
    return $this;
  }

  public function getFragment() {
    return $this->fragment;
  }

  public function setOffset($offset) {
    $this->offset = $offset;
    return $this;
  }

  public function getOffset() {
    return $this->offset;
  }

}
