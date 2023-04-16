<?php

final class HarbormasterBuildPlanDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Build Plans');
  }

  public function getPlaceholderText() {
    return pht('Type a build plan name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $results = array();

    $query = id(new HarbormasterBuildPlanQuery())
      ->setOrder('name')
      ->withDatasourceQuery($raw_query);

    $plans = $this->executeQuery($query);
    foreach ($plans as $plan) {
      $closed = null;
      if ($plan->isDisabled()) {
        $closed = pht('Disabled');
      }

      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($plan->getName())
        ->setClosed($closed)
        ->setPHID($plan->getPHID());
    }

    return $results;
  }

}
