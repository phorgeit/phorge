<?php

final class PhorgeLipsumContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'lipsum';

  public function getSourceName() {
    return pht('Lipsum');
  }

  public function getSourceDescription() {
    return pht('Test data created with bin/lipsum.');
  }

}
