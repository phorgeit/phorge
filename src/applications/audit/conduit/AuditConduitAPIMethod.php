<?php

abstract class AuditConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgeDiffusionApplication');
  }

}
