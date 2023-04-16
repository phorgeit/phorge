<?php

final class LegalpadDocumentDatasource extends PhorgeTypeaheadDatasource {

  public function isBrowsable() {
    // TODO: This should be made browsable.
    return false;
  }

  public function getBrowseTitle() {
    return pht('Browse Documents');
  }

  public function getPlaceholderText() {
    return pht('Type a document name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeLegalpadApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $results = array();

    $documents = id(new LegalpadDocumentQuery())
      ->setViewer($viewer)
      ->execute();
    foreach ($documents as $document) {
      $results[] = id(new PhorgeTypeaheadResult())
        ->setPHID($document->getPHID())
        ->setName($document->getMonogram().' '.$document->getTitle());
    }

    return $results;
  }

}
