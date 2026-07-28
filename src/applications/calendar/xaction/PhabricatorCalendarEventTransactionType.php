<?php

abstract class PhabricatorCalendarEventTransactionType
  extends PhabricatorModularTransactionType {

  public function isInheritedEdit() {
    return true;
  }

  protected function renderObjectType() {
    return pht('event');
  }

}
