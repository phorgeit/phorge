<?php

final class PhorgeDashboardPortalViewController
  extends PhorgeDashboardPortalController {

  private $portal;

  public function setPortal(PhorgeDashboardPortal $portal) {
    $this->portal = $portal;
    return $this;
  }

  public function getPortal() {
    return $this->portal;
  }

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $id = $request->getURIData('portalID');

    $portal = id(new PhorgeDashboardPortalQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$portal) {
      return new Aphront404Response();
    }

    $this->setPortal($portal);

    $engine = id(new PhorgeDashboardPortalProfileMenuEngine())
      ->setProfileObject($portal)
      ->setController($this);

    return $engine->buildResponse();
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $portal = $this->getPortal();
    if ($portal) {
      $crumbs->addTextCrumb($portal->getName(), $portal->getURI());
    }

    return $crumbs;
  }

  public function newTimelineView() {
    return $this->buildTransactionTimeline(
      $this->getPortal(),
      new PhorgeDashboardPortalTransactionQuery());
  }

}
