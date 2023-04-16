<?php

final class PhorgePeopleProfileBadgesController
  extends PhorgePeopleProfileController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $id = $request->getURIData('id');

    $user = id(new PhorgePeopleQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needProfileImage(true)
      ->executeOne();
    if (!$user) {
      return new Aphront404Response();
    }

    $class = 'PhorgeBadgesApplication';
    if (!PhorgeApplication::isClassInstalledForViewer($class, $viewer)) {
      return new Aphront404Response();
    }

    $this->setUser($user);
    $title = array(pht('Badges'), $user->getUsername());
    $header = $this->buildProfileHeader();
    $badges = $this->buildBadgesView($user);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Badges'));
    $crumbs->setBorder(true);

    $nav = $this->newNavigation(
      $user,
      PhorgePeopleProfileMenuEngine::ITEM_BADGES);

    $button = id(new PHUIButtonView())
      ->setTag('a')
      ->setIcon('fa-plus')
      ->setText(pht('Award Badge'))
      ->setWorkflow(true)
      ->setHref('/badges/award/'.$user->getID().'/');

    $header->addActionLink($button);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->addClass('project-view-home')
      ->addClass('project-view-people-home')
      ->setFooter(
        array(
          $badges,
        ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setNavigation($nav)
      ->appendChild($view);
  }

  private function buildBadgesView(PhorgeUser $user) {
    $viewer = $this->getViewer();
    $request = $this->getRequest();

    $pager = id(new AphrontCursorPagerView())
      ->readFromRequest($request);

    $query = id(new PhorgeBadgesAwardQuery())
      ->setViewer($viewer)
      ->withRecipientPHIDs(array($user->getPHID()))
      ->withBadgeStatuses(array(PhorgeBadgesBadge::STATUS_ACTIVE));

    $awards = $query->executeWithCursorPager($pager);

    if ($awards) {
      $flex = new PHUIBadgeBoxView();
      foreach ($awards as $award) {
        $badge = $award->getBadge();

        $awarder_info = array();

        $awarder_phid = $award->getAwarderPHID();
        $awarder_handle = $viewer->renderHandle($awarder_phid);
        $awarded_date = phorge_date($award->getDateCreated(), $viewer);

        $awarder_info = pht(
          'Awarded by %s',
          $awarder_handle->render());

        $item = id(new PHUIBadgeView())
          ->setIcon($badge->getIcon())
          ->setHeader($badge->getName())
          ->setSubhead($badge->getFlavor())
          ->setQuality($badge->getQuality())
          ->setHref($badge->getViewURI())
          ->addByLine($awarder_info)
          ->addByLine($awarded_date);

        $flex->addItem($item);
      }
    } else {
      $flex = id(new PHUIInfoView())
        ->setSeverity(PHUIInfoView::SEVERITY_NOTICE)
        ->appendChild(pht('User has not been awarded any badges.'));
    }

    return array(
      $flex,
      $pager,
    );
  }
}
