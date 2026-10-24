<?php

interface PhabricatorPHIDInterface {

  /** @return string|null newly-created objects and logged-out users do not have
   * a PHID. */
  public function getPHID();

}
