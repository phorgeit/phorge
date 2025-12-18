<?php

final class PhabricatorFerretMetadata extends Phobject {

  private $phid;
  private $engine;
  private $relevance;

  public function setEngine($engine) {
    $this->engine = $engine;
    return $this;
  }

  /**
   * @return PhabricatorFerretEngine A subclass of PhabricatorFerretEngine,
   *   e.g. DiffusionCommitFerretEngine or ManiphestTaskFerretEngine
   */
  public function getEngine() {
    return $this->engine;
  }

  public function setPHID($phid) {
    $this->phid = $phid;
    return $this;
  }

  /**
   * @return string PHID of a search result
   */
  public function getPHID() {
    return $this->phid;
  }

  public function setRelevance($relevance) {
    $this->relevance = $relevance;
    return $this;
  }

  /**
   * @return int
   */
  public function getRelevance() {
    return $this->relevance;
  }

  /**
   * @return PhutilSortVector
   */
  public function getRelevanceSortVector() {
    $engine = $this->getEngine();

    return id(new PhutilSortVector())
      ->addInt($engine->getObjectTypeRelevance())
      ->addInt(-$this->getRelevance());
  }

}
