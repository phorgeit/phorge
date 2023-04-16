<?php

final class PhorgePhurlRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'U';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhorgePhurlURLQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
