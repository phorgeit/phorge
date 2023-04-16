<?php

final class PhorgeCalendarExportTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'calendar';
  }

  public function getApplicationTransactionType() {
    return PhorgeCalendarExportPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeCalendarExportTransactionType';
  }

}
