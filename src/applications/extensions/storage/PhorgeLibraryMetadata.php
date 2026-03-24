<?php

/**
 * Object to describe all libraries/extensions - installed or known
 */
final class PhorgeLibraryMetadata extends PhorgeExtensionsDTO {

  protected $name;
  protected $location;
  protected $source;
  protected $status;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_NO_TABLE => true,
    ) + parent::getConfiguration();
  }

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
