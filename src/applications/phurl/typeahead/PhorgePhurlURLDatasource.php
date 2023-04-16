<?php

final class PhorgePhurlURLDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Phurl URLs');
  }

  public function getPlaceholderText() {
    return pht('Select a phurl...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgePhurlApplication';
  }

  public function loadResults() {
    $query = id(new PhorgePhurlURLQuery());
    $urls = $this->executeQuery($query);
    $results = array();
    foreach ($urls as $url) {
      $result = id(new PhorgeTypeaheadResult())
        ->setDisplayName($url->getName())
        ->setName($url->getName()." ".$url->getAlias())
        ->setPHID($url->getPHID())
        ->setAutocomplete('(('.$url->getAlias().'))')
        ->addAttribute($url->getLongURL());

      $results[] = $result;
    }

    return $this->filterResultsAgainstTokens($results);
  }

}
