<?php

final class PhorgeSettingsAdjustController
  extends PhorgeController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $preferences = PhorgeUserPreferences::loadUserPreferences($viewer);

    $editor = id(new PhorgeUserPreferencesEditor())
      ->setActor($viewer)
      ->setContentSourceFromRequest($request)
      ->setContinueOnNoEffect(true)
      ->setContinueOnMissingFields(true);

    $key = $request->getStr('key');
    $value = $request->getStr('value');

    $xactions = array();
    $xactions[] = $preferences->newTransaction($key, $value);

    $editor->applyTransactions($preferences, $xactions);

    return id(new AphrontAjaxResponse())->setContent(array());
  }
}
