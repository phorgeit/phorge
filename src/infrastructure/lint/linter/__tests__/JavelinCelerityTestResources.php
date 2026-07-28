<?php

final class JavelinCelerityTestResources
  extends CelerityResourcesOnDisk {

  /** @var CelerityResources|null */
  private $wrap;

  public function wrap(CelerityResources $resources) {
    $this->wrap = $resources;
    return $this;
  }

  public function getPathToResources() {
    return __DIR__.'/javelin/';
  }

  public function getPathToMap() {
    return __DIR__.'/map.php';
  }

  public function loadMap() {
    $map = parent::loadMap();

    if (!$this->wrap) {
      return $map;
    }

    return array_merge_recursive($map, $this->wrap->loadMap());
  }

  public function getName() {
    return 'javelinunittest';
  }

  protected function getTextFileSuffixes() {
    return array(
      'lint-test',
    );
  }

  public function getResourceData($name) {
    $data = parent::getResourceData($name);

    list($data) = preg_split('/^~{4,}\n/m', $data, 2);

    return $data;
  }

}
