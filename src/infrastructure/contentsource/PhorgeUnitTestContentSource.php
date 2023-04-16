<?php

final class PhorgeUnitTestContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'unittest';

  public function getSourceName() {
    return pht('Unit Test');
  }

  public function getSourceDescription() {
    return pht('Content created by unit tests.');
  }

}
