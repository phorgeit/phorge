<?php

final class PhabricatorTokenReceiverQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $tokenCounts = array();

  public function newResultObject() {
    return new PhabricatorTokenCount();
  }

  protected function willFilterPage(array $counts) {
    $phids = mpull($counts, 'getObjectPHID');

    $objects = id(new PhabricatorObjectQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs($phids)
      ->execute();

    // Return the objects in count order, keyed by PHID.
    $results = array();
    foreach ($counts as $count) {
      $phid = $count->getObjectPHID();
      if (isset($objects[$phid])) {
        $results[$phid] = $objects[$phid];
        $this->tokenCounts[$phid] = $count->getTokenCount();
      }
    }

    return $results;
  }

  public function getTokenCounts() {
    return $this->tokenCounts;
  }

  protected function getDefaultOrderVector() {
    return array('tokenCount', 'id');
  }

  public function getOrderableColumns() {
    return array(
      'tokenCount' => array(
        'column' => 'tokenCount',
        'type' => 'int',
      ),
    ) + parent::getOrderableColumns();
  }

  protected function newPagingMapFromPartialObject($object) {
    return array(
      'id' => (int)$object->getID(),
      'tokenCount' => (int)$object->getTokenCount(),
    );
  }

  public function getQueryApplicationClass() {
    return PhabricatorTokensApplication::class;
  }

}
