<?php

final class PhorgeLibraryQuery
  extends PhabricatorQuery {

    private $names;
    private $locations;

    public function withNames($names) {
      $this->names = $names;
      return $this;
    }

    public function withLocations($paths) {
      $this->locations = $paths;
      return $this;
    }

    public function execute() {
    $libs = $this->loadLibraries();

    $libs = mpull($libs, null, 'getName');

    if ($this->locations !== null) {
      $locations = array_fuse($this->locations);
      foreach ($libs as $name => $data) {
        if (idx($locations, $data->getLocation()) == null) {
          unset($libs[$name]);
        }
      }
    }

    return $libs;
  }

  private function loadLibraries() {
    $lib_names = PhutilBootloader::getInstance()->getAllLibraries();

    if ($this->names !== null) {
      $lib_names = array_intersect($lib_names, $this->names);
    }

    $metadata = array();

    foreach ($lib_names as $lib_name) {
      $location = phutil_get_library_root($lib_name);

      $metadata[] = id(new PhorgeLibraryMetadata())
        ->setName($lib_name)
        ->setLocation($location);
    }

    return $metadata;
  }

  public function getQueryApplicationClass() {
    return PhorgeExtensionsApplication::class;
  }

  public function newResultObject() {
    return new PhorgeLibraryMetadata();
  }

}
