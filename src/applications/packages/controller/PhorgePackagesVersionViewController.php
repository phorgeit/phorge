<?php

final class PhorgePackagesVersionViewController
  extends PhorgePackagesVersionController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $publisher_key = $request->getURIData('publisherKey');
    $package_key = $request->getURIData('packageKey');
    $full_key = $publisher_key.'/'.$package_key;
    $version_key = $request->getURIData('versionKey');

    $version = id(new PhorgePackagesVersionQuery())
      ->setViewer($viewer)
      ->withFullKeys(array($full_key))
      ->withNames(array($version_key))
      ->executeOne();
    if (!$version) {
      return new Aphront404Response();
    }

    $package = $version->getPackage();
    $publisher = $package->getPublisher();

    $crumbs = $this->buildApplicationCrumbs()
      ->addTextCrumb($publisher->getName(), $publisher->getURI())
      ->addTextCrumb($package->getName(), $package->getURI())
      ->addTextCrumb($version->getName())
      ->setBorder(true);

    $header = $this->buildHeaderView($version);
    $curtain = $this->buildCurtain($version);

    $timeline = $this->buildTransactionTimeline(
      $version,
      new PhorgePackagesVersionTransactionQuery());
    $timeline->setShouldTerminate(true);

    $version_view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn($timeline);

    return $this->newPage()
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(
        array(
          $version->getPHID(),
        ))
      ->appendChild($version_view);
  }


  private function buildHeaderView(PhorgePackagesVersion $version) {
    $viewer = $this->getViewer();
    $name = $version->getName();

    return id(new PHUIHeaderView())
      ->setViewer($viewer)
      ->setHeader($name)
      ->setPolicyObject($version)
      ->setHeaderIcon('fa-tag');
  }

  private function buildCurtain(PhorgePackagesVersion $version) {
    $viewer = $this->getViewer();
    $curtain = $this->newCurtainView($version);

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $version,
      PhorgePolicyCapability::CAN_EDIT);

    $id = $version->getID();
    $edit_uri = $this->getApplicationURI("version/edit/{$id}/");

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Edit Version'))
        ->setIcon('fa-pencil')
        ->setDisabled(!$can_edit)
        ->setHref($edit_uri));

    return $curtain;
  }

}
