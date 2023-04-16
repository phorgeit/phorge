<?php

final class DrydockBlueprintDatasource
  extends PhorgeTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type a blueprint name...');
  }

  public function getBrowseTitle() {
    return pht('Browse Blueprints');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDrydockApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $blueprints = id(new DrydockBlueprintQuery())
      ->setViewer($viewer)
      ->withDatasourceQuery($raw_query)
      ->execute();

    $handles = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(mpull($blueprints, 'getPHID'))
      ->execute();

    $results = array();
    foreach ($blueprints as $blueprint) {
      $handle = $handles[$blueprint->getPHID()];

      $result = id(new PhorgeTypeaheadResult())
        ->setName($handle->getFullName())
        ->setPHID($handle->getPHID());

      if ($blueprint->getIsDisabled()) {
        $result->setClosed(pht('Disabled'));
      }

      $result->addAttribute(
        $blueprint->getImplementation()->getBlueprintName());

      $results[] = $result;
    }

    return $results;
  }
}
