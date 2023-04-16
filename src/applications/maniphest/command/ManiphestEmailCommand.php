<?php

abstract class ManiphestEmailCommand
  extends MetaMTAEmailTransactionCommand {

  public function isCommandSupportedForObject(
    PhorgeApplicationTransactionInterface $object) {
    return ($object instanceof ManiphestTask);
  }

}
