<?php

abstract class PhabricatorAuthConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhabricatorApplication::getByClass(
      PhabricatorAuthApplication::class);
  }

}
