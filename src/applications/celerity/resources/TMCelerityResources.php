<?php

final class TMCelerityResources extends CelerityResourcesOnDisk {

  public function getName(): string {
    return 'tmphabricator';
  }

  public function getPathToMap(): string {
    return $this->getPath('resources/celerity/map.php');
  }

  public function getPathToResources(): string {
    return $this->getPath('rsrc/');
  }

  private function getPath(string $file): string {
    $root = dirname(phutil_get_library_root('tmphabricator'));
    return $root.'/'.$file;
  }

}
