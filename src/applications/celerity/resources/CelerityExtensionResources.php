<?php

/**
 * Defines Celerity resources for a Phorge extension.
 */
abstract class CelerityExtensionResources extends CelerityResourcesOnDisk {
  private $phorgeResources;

  public function __construct() {
    $this->phorgeResources = new CelerityPhabricatorResources();
  }

  final public function loadMap() {
    return array_merge_recursive(
      parent::loadMap(),
      $this->phorgeResources->loadMap());
  }

  final protected function getPathToResource($name) {
    $phorge_map = $this->phorgeResources->loadMap();

    if (isset($phorge_map['names'][$name])) {
      return $this->phorgeResources->getPathToResource($name);
    }

    return parent::getPathToResource($name);
  }

  public function getPathToMap() {
    return $this->getExtensionResourceDirectory().'celerity/map.php';
  }


  public function getPathToResources() {
    return $this->getExtensionResourceDirectory().'rsrc/';
  }

  protected function getExtensionResourceDirectory() {
    return dirname(phutil_get_library_root($this->getName())).'/resources/';
  }

}
