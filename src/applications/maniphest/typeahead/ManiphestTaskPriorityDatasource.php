<?php

final class ManiphestTaskPriorityDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Priorities');
  }

  public function getPlaceholderText() {
    return pht('Type a task priority name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeManiphestApplication';
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

    $priority_map = ManiphestTaskPriority::getTaskPriorityMap();
    foreach ($priority_map as $value => $name) {
      $result = id(new PhorgeTypeaheadResult())
        ->setIcon(ManiphestTaskPriority::getTaskPriorityIcon($value))
        ->setPHID($value)
        ->setName($name)
        ->addAttribute(pht('Priority'));

      if (ManiphestTaskPriority::isDisabledPriority($value)) {
        $result->setClosed(pht('Disabled'));
      }

      $results[$value] = $result;
    }

    return $results;
  }

}
