<?php

abstract class PhabricatorFactEngine extends Phobject {

  private $factMap;
  private $viewer;

  final public static function loadAllEngines() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->execute();
  }

  /**
   * @return array All types of facts known by this FactEngine
   */
  abstract public function newFacts();

  /**
   * @return bool
   */
  abstract public function supportsDatapointsForObject(
    PhabricatorLiskDAO $object);

  /**
   * Add new datapoints (due to a transaction) about an object
   */
  abstract public function newDatapointsForObject(PhabricatorLiskDAO $object);

  final protected function getFact($key) {
    if ($this->factMap === null) {
      $facts = $this->newFacts();
      $facts = mpull($facts, null, 'getKey');
      $this->factMap = $facts;
    }

    if (!isset($this->factMap[$key])) {
      throw new Exception(
        pht(
          'Unknown fact ("%s") for engine "%s".',
          $key,
          get_class($this)));
    }

    return $this->factMap[$key];
  }

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    if (!$this->viewer) {
      throw new PhutilInvalidStateException('setViewer');
    }

    return $this->viewer;
  }

}
