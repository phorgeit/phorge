<?php

final class PhorgeSpacesRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'S';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');
    return id(new PhorgeSpacesNamespaceQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
