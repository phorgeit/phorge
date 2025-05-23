<?php

final class PonderQuestionPHIDType extends PhabricatorPHIDType {

  const TYPECONST = 'QUES';

  public function getTypeName() {
    return pht('Ponder Question');
  }

  public function newObject() {
    return new PonderQuestion();
  }

  public function getPHIDTypeApplicationClass() {
    return PhabricatorPonderApplication::class;
  }

  protected function buildQueryForObjects(
    PhabricatorObjectQuery $query,
    array $phids) {

    return id(new PonderQuestionQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhabricatorHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $question = $objects[$phid];

      $id = $question->getID();

      $handle->setName("Q{$id}");
      $handle->setURI("/Q{$id}");
      $handle->setFullName($question->getFullTitle());
    }
  }

  /**
   * Check whether a named object is of this PHID type
   * @param string $name Object name
   * @return bool True if the named object is of this PHID type
   */
  public function canLoadNamedObject($name) {
    return preg_match('/^Q\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhabricatorObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PonderQuestionQuery())
      ->setViewer($query->getViewer())
      ->withIDs(array_keys($id_map))
      ->execute();

    $results = array();
    foreach ($objects as $id => $object) {
      foreach (idx($id_map, $id, array()) as $name) {
        $results[$name] = $object;
      }
    }

    return $results;
  }

}
