<?php

final class PhorgeIconDatasource extends PhorgeTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type an icon name...');
  }

  public function getBrowseTitle() {
    return pht('Browse Icons');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeFilesApplication';
  }

  public function loadResults() {
    $results = $this->buildResults();
    return $this->filterResultsAgainstTokens($results);
  }

  protected function renderSpecialTokens(array $values) {
    return $this->renderTokensFromResults($this->buildResults(), $values);
  }

  private function buildResults() {
    $raw_query = $this->getRawQuery();

    $icons = id(new PHUIIconView())->getIcons();

    $results = array();
    foreach ($icons as $icon) {
      $display_name = str_replace('fa-', '', $icon);
      $result = id(new PhorgeTypeaheadResult())
        ->setPHID($icon)
        ->setName($icon)
        ->setIcon($icon)
        ->setDisplayname($display_name)
        ->addAttribute($icon);

      $results[$icon] = $result;
    }
    return $results;
  }

}
