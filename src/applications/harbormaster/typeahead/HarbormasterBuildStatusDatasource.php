<?php

final class HarbormasterBuildStatusDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Choose Build Statuses');
  }

  public function getPlaceholderText() {
    return pht('Type a build status name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  public function loadResults() {
    $results = $this->buildResults();
    return $this->filterResultsAgainstTokens($results);
  }

  public function renderTokens(array $values) {
    return $this->renderTokensFromResults($this->buildResults(), $values);
  }

  private function buildResults() {
    $results = array();

    $status_map = HarbormasterBuildStatus::getBuildStatusMap();
    foreach ($status_map as $value => $name) {
      $result = id(new PhorgeTypeaheadResult())
        ->setIcon(HarbormasterBuildStatus::getBuildStatusIcon($value))
        ->setColor(HarbormasterBuildStatus::getBuildStatusColor($value))
        ->setPHID($value)
        ->setName($name)
        ->addAttribute(pht('Status'));

      $results[$value] = $result;
    }

    return $results;
  }

}
