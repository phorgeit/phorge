<?php

final class PhorgeMetaMTAApplicationEmailDatasource
  extends PhorgeTypeaheadDatasource {

  public function isBrowsable() {
    // TODO: Make this browsable.
    return false;
  }

  public function getBrowseTitle() {
    return pht('Browse Email Addresses');
  }

  public function getPlaceholderText() {
    return pht('Type an application email address...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeMetaMTAApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $emails = id(new PhorgeMetaMTAApplicationEmailQuery())
      ->setViewer($viewer)
      ->withAddressPrefix($raw_query)
      ->setLimit($this->getLimit())
      ->execute();

    if ($emails) {
      $handles = id(new PhorgeHandleQuery())
        ->setViewer($viewer)
        ->withPHIDs(mpull($emails, 'getPHID'))
        ->execute();
    } else {
      $handles = array();
    }

    $results = array();
    foreach ($handles as $handle) {
      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($handle->getName())
        ->setPHID($handle->getPHID());
    }

    return $results;
  }

}
