<?php

final class PhorgeLibraryQuery
  extends PhabricatorOffsetPagedQuery {

  private $phids;

  public function execute() {
    $libs = $this->loadLibraries();

    if ($this->phids) {
      $map = array_fill_keys($this->phids, true);
      foreach ($libs as $key => $token) {
        if (empty($map[$token->getPHID()])) {
          unset($libs[$key]);
        }
      }
    }

    $libs = mpull($libs, null, 'getName');

    return $libs;
  }

  private function loadLibraries() {
    $all_libs = PhutilBootloader::getInstance()->getAllLibraries();

    $metadata = array();

    foreach ($all_libs as $lib_name) {
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
