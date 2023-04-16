<?php

final class PhorgeSpacesNamespaceDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Spaces');
  }

  public function getPlaceholderText() {
    return pht('Type a space name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeSpacesApplication';
  }

  public function loadResults() {
    $query = id(new PhorgeSpacesNamespaceQuery());

    $spaces = $this->executeQuery($query);
    $results = array();
    foreach ($spaces as $space) {
      $full_name = pht(
        '%s %s',
        $space->getMonogram(),
        $space->getNamespaceName());

      $result = id(new PhorgeTypeaheadResult())
        ->setName($full_name)
        ->setPHID($space->getPHID());

      if ($space->getIsArchived()) {
        $result->setClosed(pht('Archived'));
      }

      $results[] = $result;
    }

    return $this->filterResultsAgainstTokens($results);
  }

}
