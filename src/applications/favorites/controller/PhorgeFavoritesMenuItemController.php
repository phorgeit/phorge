<?php

final class PhorgeFavoritesMenuItemController
  extends PhorgeFavoritesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $application = 'PhorgeFavoritesApplication';
    $favorites = id(new PhorgeApplicationQuery())
      ->setViewer($viewer)
      ->withClasses(array($application))
      ->withInstalled(true)
      ->executeOne();

    $engine = id(new PhorgeFavoritesProfileMenuEngine())
      ->setProfileObject($favorites)
      ->setCustomPHID($viewer->getPHID())
      ->setController($this);

    return $engine->buildResponse();
  }

}
