<?php

final class PhorgeConfigSettingsHistoryController
  extends PhorgeConfigSettingsController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $xactions = id(new PhorgeConfigTransactionQuery())
      ->setViewer($viewer)
      ->needComments(true)
      ->execute();

    $object = new PhorgeConfigEntry();

    $xaction = $object->getApplicationTransactionTemplate();

    $timeline = id(new PhorgeApplicationTransactionView())
      ->setViewer($viewer)
      ->setTransactions($xactions)
      ->setRenderAsFeed(true)
      ->setObjectPHID(PhorgePHIDConstants::PHID_VOID);

    $timeline->setShouldTerminate(true);

    $title = pht('Settings History');
    $header = $this->buildHeaderView($title);

    $nav = $this->newNavigation('history');

    $crumbs = $this->newCrumbs()
      ->addTextCrumb($title);

    $content = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter($timeline);

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav)
      ->appendChild($content);
  }

}
