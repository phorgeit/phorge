<?php

final class PhorgeXHProfProfileController
  extends PhorgeXHProfController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $phid = $request->getURIData('phid');

    $file = id(new PhorgeFileQuery())
      ->setViewer($request->getUser())
      ->withPHIDs(array($phid))
      ->executeOne();
    if (!$file) {
      return new Aphront404Response();
    }

    $data = $file->loadFileData();
    try {
      $data = phutil_json_decode($data);
    } catch (PhutilJSONParserException $ex) {
      throw new PhutilProxyException(
        pht('Failed to unserialize XHProf profile!'),
        $ex);
    }

    $symbol = $request->getStr('symbol');

    $is_framed = $request->getBool('frame');

    if ($symbol) {
      $view = new PhorgeXHProfProfileSymbolView();
      $view->setSymbol($symbol);
    } else {
      $view = new PhorgeXHProfProfileTopLevelView();
      $view->setFile($file);
      $view->setLimit(100);
    }

    $view->setBaseURI($request->getRequestURI()->getPath());
    $view->setIsFramed($is_framed);
    $view->setProfileData($data);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('%s Profile', $symbol));

    $title = pht('Profile');

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setFrameable(true)
      ->setShowChrome(false)
      ->setDisableConsole(true)
      ->appendChild($view);
  }
}
