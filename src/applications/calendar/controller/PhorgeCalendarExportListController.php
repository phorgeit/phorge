<?php

final class PhorgeCalendarExportListController
  extends PhorgeCalendarController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeCalendarExportSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $doc_name = 'Calendar User Guide: Exporting Events';
    $doc_href = PhorgeEnv::getDoclink($doc_name);

    $crumbs->addAction(
      id(new PHUIListItemView())
        ->setName(pht('Guide: Exporting Events'))
        ->setIcon('fa-book')
        ->setHref($doc_href));

    return $crumbs;
  }

}
