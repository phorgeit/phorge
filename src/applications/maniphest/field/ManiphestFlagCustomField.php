<?php

// I'm not sure this is the right use for the Proxy capability.
// Probably should just use Traits for this.
final class ManiphestFlagCustomField extends ManiphestCustomField {

  public function __construct() {
    $this->setProxy(new PhorgeFlagFlaggedObjectCustomField());
  }

  public function canSetProxy() {
    return true;
  }

  public function newStorageObject() {
    return $this->getProxy()->newStorageObject();
  }
}
