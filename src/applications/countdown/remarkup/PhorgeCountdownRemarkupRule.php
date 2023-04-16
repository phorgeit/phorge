<?php

final class PhorgeCountdownRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'C';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');
    return id(new PhorgeCountdownQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

  protected function renderObjectEmbed(
    $object,
    PhorgeObjectHandle $handle,
    $options) {

    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhorgeCountdownView())
      ->setCountdown($object)
      ->setUser($viewer);
  }

}
