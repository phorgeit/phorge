<?php

abstract class PhorgeAuthSSHPrivateKeyException
  extends Exception {

  abstract public function isFormatException();
  abstract public function isPassphraseException();

}
