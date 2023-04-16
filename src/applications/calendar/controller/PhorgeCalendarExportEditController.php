<?php

final class PhorgeCalendarExportEditController
  extends PhorgeCalendarController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeCalendarExportEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
