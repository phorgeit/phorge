<?php

final class PhamePostRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'J';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhamePostQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
