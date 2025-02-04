<?php

final class PhabricatorTokenDatasource
  extends PhabricatorTypeaheadDatasource {

  public function getPlaceholderText() {
    return pht('Type a Token name...');
  }

  public function getBrowseTitle() {
    return pht('Browse Tokens');
  }

  public function getDatasourceApplicationClass() {
    return PhabricatorTokensApplication::class;
  }

  public function loadResults() {
    $viewer = $this->getViewer();

    $tokens = id(new PhabricatorTokenQuery())
      ->setViewer($viewer)
      ->execute();

    $handles = id(new PhabricatorHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(mpull($tokens, 'getPHID'))
      ->execute();

    $results = array();
    foreach ($tokens as $token) {
      $handle = $handles[$token->getPHID()];

      $result = id(new PhabricatorTypeaheadResult())
        ->setName($handle->getFullName())
        ->setPHID($handle->getPHID());
      $results[] = $result;
    }

    return $results;
  }
}
