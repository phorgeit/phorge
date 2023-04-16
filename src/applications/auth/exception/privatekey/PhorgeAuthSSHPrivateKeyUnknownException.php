<?php

final class PhorgeAuthSSHPrivateKeyUnknownException
  extends PhorgeAuthSSHPrivateKeyException {

  public function isFormatException() {
    return true;
  }

  public function isPassphraseException() {
    return true;
  }

}
