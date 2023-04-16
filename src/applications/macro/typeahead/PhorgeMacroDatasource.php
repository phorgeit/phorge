<?php

final class PhorgeMacroDatasource extends PhorgeTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type a macro name...');
  }

  public function getBrowseTitle() {
    return pht('Browse Macros');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeMacroApplication';
  }

  public function loadResults() {
    $raw_query = $this->getRawQuery();

    $query = id(new PhorgeMacroQuery())
      ->setOrder('name')
      ->withNamePrefix($raw_query);
    $macros = $this->executeQuery($query);

    $results = array();
    foreach ($macros as $macro) {
      $closed = null;
      if ($macro->getIsDisabled()) {
        $closed = pht('Disabled');
      }

      $results[] = id(new PhorgeTypeaheadResult())
        ->setPHID($macro->getPHID())
        ->setClosed($closed)
        ->setName($macro->getName());
    }

    return $results;
  }

}
