<?php

abstract class PhorgeCalendarEventEmailCommand
  extends MetaMTAEmailTransactionCommand {

  public function isCommandSupportedForObject(
    PhorgeApplicationTransactionInterface $object) {
    return ($object instanceof PhorgeCalendarEvent);
  }

}
