<?php

abstract class PhabricatorPeopleController extends PhabricatorController {

  public function shouldRequireAdmin() {
    return true;
  }

  /**
   * return AphrontSideNavFilterView
   */
  public function buildSideNavView($for_app = false) {
    // we are on /people/*
    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

    $viewer = $this->getRequest()->getUser();
    id(new PhabricatorPeopleSearchEngine())
      ->setViewer($viewer)
      ->addNavigationItems($nav->getMenu());

    if ($viewer->getIsAdmin()) {
      $nav->addLabel(pht('User Administration'));
      $nav->addFilter('logs', pht('Activity Logs'));
      $nav->addFilter('invite', pht('Email Invitations'));
    }

    return $nav;
  }

  public function buildApplicationMenu() {
    if ($this->getRequest()->getURIData('username')) {
      // we are on /p/name/ so return the default user profile sidebar
      return parent::buildApplicationMenu();
    }
    return $this->buildSideNavView(true)->getMenu();
  }

}
