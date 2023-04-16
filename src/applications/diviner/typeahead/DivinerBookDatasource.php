<?php

final class DivinerBookDatasource extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Books');
  }

  public function getPlaceholderText() {
    return pht('Type a book name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDivinerApplication';
  }

  public function loadResults() {
    $raw_query = $this->getRawQuery();

    $query = id(new DivinerBookQuery())
      ->setOrder('name')
      ->withNamePrefix($raw_query);
    $books = $this->executeQuery($query);

    $results = array();
    foreach ($books as $book) {
      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($book->getTitle())
        ->setURI('/book/'.$book->getName().'/')
        ->setPHID($book->getPHID())
        ->setPriorityString($book->getName());
    }

    return $results;
  }

}
