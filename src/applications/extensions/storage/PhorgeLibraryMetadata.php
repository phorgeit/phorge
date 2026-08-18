<?php

/**
 * Object to describe installed libraries/extensions
 */
final class PhorgeLibraryMetadata extends PhorgeExtensionsDTO {

  protected $name;
  protected $location;
  protected $source;

  public function isCoreLibrary() {
    switch ($this->name) {
      case 'phorge':
      case 'arcanist':
        return true;
      default:
        return false;
    }
  }

}
