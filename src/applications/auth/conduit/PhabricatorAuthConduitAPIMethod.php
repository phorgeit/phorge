<?php

abstract class PhabricatorAuthConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhabricatorApplication::getByClass(
      PhabricatorAuthApplication::class);
  }

  public function getMethodStatusDescription() {
    return pht('These methods are recently introduced and subject to change.');
  }

}
