<?php

abstract class SlowvoteConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgeSlowvoteApplication');
  }

}
