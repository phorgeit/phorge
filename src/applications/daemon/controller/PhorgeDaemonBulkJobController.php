<?php

abstract class PhorgeDaemonBulkJobController
  extends PhorgeDaemonController {

  public function shouldRequireAdmin() {
    return false;
  }

  public function shouldAllowPublic() {
    return true;
  }

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgeWorkerBulkJobSearchEngine());
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Bulk Jobs'), '/daemon/bulk/');
    return $crumbs;
  }

}
