<?php

final class PhorgeFaxContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'fax';

  public function getSourceName() {
    return pht('Fax');
  }

  public function getSourceDescription() {
    return pht('Content received via fax (telefacsimile).');
  }

}
