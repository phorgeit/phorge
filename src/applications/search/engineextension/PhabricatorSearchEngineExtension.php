<?php

abstract class PhabricatorSearchEngineExtension extends Phobject {

  private $viewer;
  private $searchEngine;

  /**
   * @return string The EXTENSIONKEY of the PhabricatorSearchEngineExtension
   *   subclass
   */
  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final public function setViewer($viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  /**
   * @return PhabricatorUser
   */
  final public function getViewer() {
    return $this->viewer;
  }

  final public function setSearchEngine(
    PhabricatorApplicationSearchEngine $engine) {
    $this->searchEngine = $engine;
    return $this;
  }

  /**
   * @return PhabricatorApplicationSearchEngine A subclass of
   *   PhabricatorApplicationSearchEngine
   */
  final public function getSearchEngine() {
    return $this->searchEngine;
  }

  /**
   * @return bool
   */
  abstract public function isExtensionEnabled();
  /**
   * @return string  Description of the Search Engine Extension
   */
  abstract public function getExtensionName();
  /**
   * @return bool
   */
  abstract public function supportsObject($object);

  public function getExtensionOrder() {
    return 7000;
  }

  /**
   * @return array<PhabricatorSearchField> Subclasses of
   *   PhabricatorSearchField, or an empty array
   */
  public function getSearchFields($object) {
    return array();
  }

  /**
   * @return array<PhabricatorSearchEngineAttachment> Subclasses of
   *   PhabricatorSearchEngineAttachment, or an empty array
   */
  public function getSearchAttachments($object) {
    return array();
  }

  /**
   * Add additional parameters to the $query based on elements in the $map
   * @param $object A subclass of PhabricatorLiskDAO - a storage object, e.g.
   *   ManiphestTask or PhabricatorDashboardPortal
   * @param $query A corresponding subclass of
   *   PhabricatorCursorPagedPolicyAwareQuery, e.g. ManiphestTaskQuery or
   *   PhabricatorDashboardPortalQuery
   * @param PhabricatorSavedQuery $saved
   * @param array $map
   * @return void
   */
  public function applyConstraintsToQuery(
    $object,
    $query,
    PhabricatorSavedQuery $saved,
    array $map) {
    return;
  }

  public function getFieldSpecificationsForConduit($object) {
    return array();
  }

  public function loadExtensionConduitData(array $objects) {
    return null;
  }

  public function getFieldValuesForConduit($object, $data) {
    return array();
  }

  /**
   * @return map<string, PhabricatorSearchEngineExtension> Array of
   *   PhabricatorSearchEngineExtension extension keys and the
   *   PhabricatorSearchEngineExtension subclasses
   */
  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->setSortMethod('getExtensionOrder')
      ->execute();
  }

  final public static function getAllEnabledExtensions() {
    $extensions = self::getAllExtensions();

    foreach ($extensions as $key => $extension) {
      if (!$extension->isExtensionEnabled()) {
        unset($extensions[$key]);
      }
    }

    return $extensions;
  }

}
