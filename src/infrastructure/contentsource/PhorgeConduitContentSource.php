<?php

final class PhorgeConduitContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'conduit';

  public function getSourceName() {
    return pht('Conduit');
  }

  public function getSourceDescription() {
    return pht('Content from the Conduit API.');
  }

}
