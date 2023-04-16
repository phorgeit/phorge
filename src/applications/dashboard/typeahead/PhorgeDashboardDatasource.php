<?php

final class PhorgeDashboardDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Dashboards');
  }

  public function getPlaceholderText() {
    return pht('Type a dashboard name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  public function loadResults() {
    $query = id(new PhorgeDashboardQuery());

    $this->applyFerretConstraints(
      $query,
      id(new PhorgeDashboard())->newFerretEngine(),
      'title',
      $this->getRawQuery());

    $dashboards = $this->executeQuery($query);
    $results = array();
    foreach ($dashboards as $dashboard) {
      $result = id(new PhorgeTypeaheadResult())
        ->setName($dashboard->getName())
        ->setPHID($dashboard->getPHID())
        ->addAttribute(pht('Dashboard'));

      if ($dashboard->isArchived()) {
        $result->setClosed(pht('Archived'));
      }

      $results[] = $result;
    }

    return $this->filterResultsAgainstTokens($results);
  }

}
