<?php

final class PhorgeAuthSSHPrivateKeyFormatException
  extends PhorgeAuthSSHPrivateKeyException {

  public function isFormatException() {
    return true;
  }

  public function isPassphraseException() {
    return false;
  }

}
