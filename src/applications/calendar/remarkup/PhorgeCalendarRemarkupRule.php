<?php

final class PhorgeCalendarRemarkupRule
  extends PhorgeObjectRemarkupRule {

  protected function getObjectNamePrefix() {
    return 'E';
  }

  protected function loadObjects(array $ids) {
    $viewer = $this->getEngine()->getConfig('viewer');

    return id(new PhorgeCalendarEventQuery())
      ->setViewer($viewer)
      ->withIDs($ids)
      ->execute();
  }

}
