<?php

abstract class PhorgeExtensionsDTO extends PhabricatorLiskDAO {

  public function getApplicationName() {
    return 'extensions';
  }

}
