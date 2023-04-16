<?php

abstract class PhrequentConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgePhrequentApplication');
  }

}
