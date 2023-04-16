<?php

final class DifferentialRemarkupRule extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'D';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');
    return id(new DifferentialRevisionQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
