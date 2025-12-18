<?php

abstract class NuanceConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhabricatorApplication::getByClass(
      PhabricatorNuanceApplication::class);
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_UNSTABLE;
  }

}
