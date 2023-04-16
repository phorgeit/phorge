<?php

final class DivinerBookPHIDType extends PhorgePHIDType {

  const TYPECONST = 'BOOK';

  public function getTypeName() {
    return pht('Diviner Book');
  }

  public function newObject() {
    return new DivinerLiveBook();
  }

  public function getTypeIcon() {
    return 'fa-book';
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDivinerApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DivinerBookQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $book = $objects[$phid];

      $name = $book->getName();

      $handle
        ->setName($book->getShortTitle())
        ->setFullName($book->getTitle())
        ->setURI("/book/{$name}/");
    }
  }

}
