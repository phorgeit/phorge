<?php

final class PhorgeFlagFlaggedObjectFieldStorage extends Phobject {

  private $viewer;

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getStorageSourceKey() {
    return 'flags/flag';
  }

  public function loadStorageSourceData(array $fields) {

    $objects = mpull($fields, 'getObject');
    $object_phids = mpull($objects, 'getPHID');
    $flags = (new PhabricatorFlagQuery())
      ->setViewer($this->viewer)
      ->withOwnerPHIDs(array($this->viewer->getPHID()))
      ->withObjectPHIDs($object_phids)
      ->execute();
    $flags = mpull($flags, null, 'getObjectPHID');

    $result = array();
    foreach ($fields as $key => $field) {
      $target_phid = $field->getObject()->getPHID();
      $result[$key] = idx($flags, $target_phid);
    }

    return $result;
  }

}
