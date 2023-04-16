<?php

final class PhorgeCalendarEventFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'calendar';
  }

  public function getScopeName() {
    return 'event';
  }

  public function newSearchEngine() {
    return new PhorgeCalendarEventSearchEngine();
  }

}
