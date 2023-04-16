<?php

final class PhorgeBadgesRecipientsController
  extends PhorgeBadgesProfileController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $badge = id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$badge) {
      return new Aphront404Response();
    }
    $this->setBadge($badge);

    $awards = id(new PhorgeBadgesAwardQuery())
      ->setViewer($viewer)
      ->withBadgePHIDs(array($badge->getPHID()))
      ->execute();

    $recipient_phids = mpull($awards, 'getRecipientPHID');
    $recipient_phids = array_reverse($recipient_phids);
    $handles = $this->loadViewerHandles($recipient_phids);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Recipients'));
    $crumbs->setBorder(true);
    $title = $badge->getName();

    $header = $this->buildHeaderView();

    $recipient_list = id(new PhorgeBadgesRecipientsListView())
      ->setBadge($badge)
      ->setAwards($awards)
      ->setHandles($handles)
      ->setUser($viewer);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setFooter(array(
          $recipient_list,
        ));

    $navigation = $this->buildSideNavView('recipients');

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(array($badge->getPHID()))
      ->setNavigation($navigation)
      ->appendChild($view);
  }

}
