<?php

final class PhorgeUserLogTypeDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Log Types');
  }

  public function getPlaceholderText() {
    return pht('Type a log type name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  public function loadResults() {
    $results = $this->buildResults();
    return $this->filterResultsAgainstTokens($results);
  }

  protected function renderSpecialTokens(array $values) {
    return $this->renderTokensFromResults($this->buildResults(), $values);
  }

  private function buildResults() {
    $results = array();

    $type_map = PhorgeUserLogType::getAllLogTypes();
    foreach ($type_map as $type_key => $type) {

      $result = id(new PhorgeTypeaheadResult())
        ->setPHID($type_key)
        ->setName($type->getLogTypeName());

      $results[$type_key] = $result;
    }

    return $results;
  }

}
