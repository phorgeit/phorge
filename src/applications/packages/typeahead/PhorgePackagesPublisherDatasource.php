<?php

final class PhorgePackagesPublisherDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Package Publishers');
  }

  public function getPlaceholderText() {
    return pht('Type a publisher name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $publisher_query = id(new PhorgePackagesPublisherQuery());
    $publishers = $this->executeQuery($publisher_query);

    $results = array();
    foreach ($publishers as $publisher) {
      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($publisher->getName())
        ->setPHID($publisher->getPHID());
    }

    return $this->filterResultsAgainstTokens($results);
  }

}
