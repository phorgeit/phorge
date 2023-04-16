<?php

final class PhorgeDashboardPortalDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Portals');
  }

  public function getPlaceholderText() {
    return pht('Type a portal name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  public function loadResults() {
    $results = $this->buildResults();
    return $this->filterResultsAgainstTokens($results);
  }

  protected function renderSpecialTokens(array $values) {
    return $this->renderTokensFromResults($this->buildResults(), $values);
  }

  public function buildResults() {
    $query = new PhorgeDashboardPortalQuery();

    $this->applyFerretConstraints(
      $query,
      id(new PhorgeDashboardPortal())->newFerretEngine(),
      'title',
      $this->getRawQuery());

    $portals = $this->executeQuery($query);

    $results = array();
    foreach ($portals as $portal) {
      $result = id(new PhorgeTypeaheadResult())
        ->setName($portal->getObjectName().' '.$portal->getName())
        ->setPHID($portal->getPHID())
        ->setIcon('fa-compass');

      $results[] = $result;
    }

    return $results;
  }

}
