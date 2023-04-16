<?php

final class PhorgeCalendarEventSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'calendar.event.search';
  }

  public function newSearchEngine() {
    return new PhorgeCalendarEventSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about events.');
  }

}
