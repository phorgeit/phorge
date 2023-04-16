<?php

abstract class PhorgeAuthSSHPrivateKeyPassphraseException
  extends PhorgeAuthSSHPrivateKeyException {

  final public function isFormatException() {
    return false;
  }

  final public function isPassphraseException() {
    return true;
  }

}
