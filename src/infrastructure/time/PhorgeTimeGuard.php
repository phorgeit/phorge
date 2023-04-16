<?php

final class PhorgeTimeGuard extends Phobject {

  private $frameKey;

  public function __construct($frame_key) {
    $this->frameKey = $frame_key;
  }

  public function __destruct() {
    PhorgeTime::popTime($this->frameKey);
  }

}
