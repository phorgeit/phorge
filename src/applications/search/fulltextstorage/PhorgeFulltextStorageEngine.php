<?php

/**
 * Base class for Phorge search engine providers. Each engine must offer
 * three capabilities: indexing, searching, and reconstruction (this can be
 * stubbed out if an engine can't reasonably do it, it is used for debugging).
 */
abstract class PhorgeFulltextStorageEngine extends Phobject {

  protected $service;

  public function getHosts() {
    return $this->service->getHosts();
  }

  public function setService(PhorgeSearchService $service) {
    $this->service = $service;
    return $this;
  }

  /**
   * @return PhorgeSearchService
   */
  public function getService() {
    return $this->service;
  }

  /**
   * Implementations must return a prototype host instance which is cloned
   * by the PhorgeSearchService infrastructure to configure each engine.
   * @return PhorgeSearchHost
   */
  abstract public function getHostType();

/* -(  Engine Metadata  )---------------------------------------------------- */

  /**
   * Return a unique, nonempty string which identifies this storage engine.
   *
   * @return string Unique string for this engine, max length 32.
   * @task meta
   */
  abstract public function getEngineIdentifier();

/* -(  Managing Documents  )------------------------------------------------- */

  /**
   * Update the index for an abstract document.
   *
   * @param PhorgeSearchAbstractDocument Document to update.
   * @return void
   */
  abstract public function reindexAbstractDocument(
    PhorgeSearchAbstractDocument $document);

  /**
   * Execute a search query.
   *
   * @param PhorgeSavedQuery A query to execute.
   * @return list A list of matching PHIDs.
   */
  abstract public function executeSearch(PhorgeSavedQuery $query);

  /**
   * Does the search index exist?
   *
   * @return bool
   */
  abstract public function indexExists();

  /**
    * Implementations should override this method to return a dictionary of
    * stats which are suitable for display in the admin UI.
    */
  abstract public function getIndexStats();


  /**
   * Is the index in a usable state?
   *
   * @return bool
   */
  public function indexIsSane() {
    return $this->indexExists();
  }

  /**
   * Do any sort of setup for the search index.
   *
   * @return void
   */
  public function initIndex() {}


  public function getFulltextTokens() {
    return array();
  }

}
