<?php

final class PhorgeCalendarImportTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'calendar';
  }

  public function getApplicationTransactionType() {
    return PhorgeCalendarImportPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeCalendarImportTransactionType';
  }

}
