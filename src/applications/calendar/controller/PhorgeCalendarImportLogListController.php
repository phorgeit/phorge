<?php

final class PhorgeCalendarImportLogListController
  extends PhorgeCalendarController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeCalendarImportLogSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
