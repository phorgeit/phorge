<?php

final class PhorgeCalendarImportListController
  extends PhorgeCalendarController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeCalendarImportSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $crumbs->addAction(
      id(new PHUIListItemView())
        ->setName(pht('Import Events'))
        ->setHref($this->getApplicationURI('import/edit/'))
        ->setIcon('fa-upload'));

    return $crumbs;
  }


}
