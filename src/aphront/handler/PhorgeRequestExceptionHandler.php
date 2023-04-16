<?php

abstract class PhorgeRequestExceptionHandler
  extends AphrontRequestExceptionHandler {

  protected function isPhorgeSite(AphrontRequest $request) {
    $site = $request->getSite();
    if (!$site) {
      return false;
    }

    return ($site instanceof PhorgeSite);
  }

  protected function getViewer(AphrontRequest $request) {
    $viewer = $request->getUser();

    if ($viewer) {
      return $viewer;
    }

    // If we hit an exception very early, we won't have a user yet.
    return new PhorgeUser();
  }

}
