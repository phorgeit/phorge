<?php

final class PhorgeOwnersPackageRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'O';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhorgeOwnersPackageQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
