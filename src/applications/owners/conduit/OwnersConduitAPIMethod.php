<?php

abstract class OwnersConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass('PhorgeOwnersApplication');
  }

}
