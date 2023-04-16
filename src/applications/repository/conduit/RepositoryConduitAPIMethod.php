<?php

abstract class RepositoryConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgeDiffusionApplication');
  }

}
