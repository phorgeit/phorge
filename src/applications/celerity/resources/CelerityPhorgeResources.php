<?php

/**
 * Defines Phorge's static resources.
 */
final class CelerityPhorgeResources extends CelerityResourcesOnDisk {

  public function getName() {
    return 'phorge';
  }

  public function getPathToResources() {
    return $this->getPhorgePath('webroot/');
  }

  public function getPathToMap() {
    return $this->getPhorgePath('resources/celerity/map.php');
  }

  private function getPhorgePath($to_file) {
    return dirname(phutil_get_library_root('phorge')).'/'.$to_file;
  }

  public function getResourcePackages() {
    return include $this->getPhorgePath('resources/celerity/packages.php');
  }

}
