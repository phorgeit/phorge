<?php

final class HeraldWebhookDatasource
  extends PhorgeTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type a webhook name...');
  }

  public function getBrowseTitle() {
    return pht('Browse Webhooks');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $hooks = id(new HeraldWebhookQuery())
      ->setViewer($viewer)
      ->execute();

    $handles = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(mpull($hooks, 'getPHID'))
      ->execute();

    $results = array();
    foreach ($hooks as $hook) {
      $handle = $handles[$hook->getPHID()];

      $result = id(new PhorgeTypeaheadResult())
        ->setName($handle->getFullName())
        ->setPHID($handle->getPHID());

      if ($hook->isDisabled()) {
        $result->setClosed(pht('Disabled'));
      }

      $results[] = $result;
    }

    return $results;
  }
}
