<?php

final class PhorgeCalendarEventEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'calendar.event.edit';
  }

  public function newEditEngine() {
    return new PhorgeCalendarEventEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new event or edit an existing one.');
  }

}
