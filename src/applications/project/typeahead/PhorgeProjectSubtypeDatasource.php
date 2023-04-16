<?php

final class PhorgeProjectSubtypeDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Subtypes');
  }

  public function getPlaceholderText() {
    return pht('Type a project subtype name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeProjectApplication';
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

    $subtype_map = id(new PhorgeProject())->newEditEngineSubtypeMap();
    foreach ($subtype_map->getSubtypes() as $key => $subtype) {

      $result = id(new PhorgeTypeaheadResult())
        ->setIcon($subtype->getIcon())
        ->setColor($subtype->getColor())
        ->setPHID($key)
        ->setName($subtype->getName());

      $results[$key] = $result;
    }

    return $results;
  }

}
