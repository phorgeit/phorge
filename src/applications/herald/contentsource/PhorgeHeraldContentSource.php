<?php

final class PhorgeHeraldContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'herald';

  public function getSourceName() {
    return pht('Herald');
  }

  public function getSourceDescription() {
    return pht('Changes triggered by Herald rules.');
  }

}
