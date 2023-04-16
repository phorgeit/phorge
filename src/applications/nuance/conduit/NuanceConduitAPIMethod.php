<?php

abstract class NuanceConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass('PhorgeNuanceApplication');
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_UNSTABLE;
  }

}
