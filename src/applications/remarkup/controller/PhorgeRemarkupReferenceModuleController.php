<?php

final class PhorgeRemarkupReferenceModuleController
  extends PhorgeRemarkupReferenceController {

  public function handleRequest(AphrontRequest $request) {
    $request = $this->getRequest();

    $key = $request->getURIData('module', 'remarkup');
    $module = PhorgeRemarkupReferenceModule::findModule($key);

    if (!$module) {
      return new Aphront404Response();
    }

    $content = $module->getContent();
    $title = $module->getTitle();

    $document = $this->renderContent($content);

    $crumbs = $this->buildApplicationCrumbs()
      ->addTextCrumb($title);

    $navigation = $this->buildSideNavView();
    $navigation->selectFilter($key.'/');

    return $this->newPage()
      ->setTitle($title)
      ->setNavigation($navigation)
      ->setCrumbs($crumbs)
      ->appendChild($document);
  }

}
