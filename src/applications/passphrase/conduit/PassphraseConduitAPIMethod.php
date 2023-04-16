<?php

abstract class PassphraseConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhorgeApplication::getByClass(
      'PhorgePassphraseApplication');
  }

}
