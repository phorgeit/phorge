<?php

final class SlowvoteRemarkupRule extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'V';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhorgeSlowvoteQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->needOptions(true)
      ->needChoices(true)
      ->needViewerChoices(true)
      ->execute();
  }

  protected function renderObjectEmbed(
    $object,
    PhorgeObjectHandle $handle,
    $options) {

    $viewer = $this->getEngine()->getConfig('viewer');

    $embed = id(new SlowvoteEmbedView())
      ->setUser($viewer)
      ->setPoll($object);

    return $embed;
  }

}
