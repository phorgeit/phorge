<?php

abstract class PhorgeCalendarEventTransactionType
  extends PhorgeModularTransactionType {

  public function isInheritedEdit() {
    return true;
  }

}
