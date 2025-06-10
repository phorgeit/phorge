<?php

final class DrydockLeaseViewController extends DrydockLeaseController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $lease = id(new DrydockLeaseQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needUnconsumedCommands(true)
      ->executeOne();
    if (!$lease) {
      return new Aphront404Response();
    }

    $id = $lease->getID();
    $lease_uri = $this->getApplicationURI("lease/{$id}/");

    $title = pht('Lease %d', $lease->getID());

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setHeaderIcon('fa-link')
      ->setStatus(
        $lease->getStatusIcon(),
        $lease->getStatusColor(),
        $lease->getStatusDisplayName());

    if ($lease->isReleasing()) {
      $header->addTag(
        id(new PHUITagView())
          ->setType(PHUITagView::TYPE_SHADE)
          ->setIcon('fa-exclamation-triangle')
          ->setColor('red')
          ->setName('Releasing'));
    }

    $curtain = $this->buildCurtain($lease);
    $properties = $this->buildPropertyListView($lease);

    $log_query = id(new DrydockLogQuery())
      ->withLeasePHIDs(array($lease->getPHID()));

    $log_table = $this->buildLogTable($log_query)
      ->setHideLeases(true);

    $logs = $this->buildLogBox(
      $log_table,
      $this->getApplicationURI("lease/{$id}/logs/query/all/"));

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb($title, $lease_uri);
    $crumbs->setBorder(true);

    $locks = $this->buildLocksTab($lease->getPHID());
    $commands = $this->buildCommandsTab($lease->getPHID());

    $tab_group = id(new PHUITabGroupView())
      ->addTab(
        id(new PHUITabView())
          ->setName(pht('Properties'))
          ->setKey('properties')
          ->appendChild($properties))
      ->addTab(
        id(new PHUITabView())
          ->setName(pht('Slot Locks'))
          ->setKey('locks')
          ->appendChild($locks))
      ->addTab(
        id(new PHUITabView())
          ->setName(pht('Commands'))
          ->setKey('commands')
          ->appendChild($commands));

    $object_box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Properties'))
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->addTabGroup($tab_group);

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->setMainColumn(array(
        $object_box,
        $logs,
      ));

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild(
        array(
          $view,
      ));

  }

  private function buildCurtain(DrydockLease $lease) {
    $viewer = $this->getViewer();

    $curtain = $this->newCurtainView($lease);
    $id = $lease->getID();

    $can_release = $lease->canRelease();
    if ($lease->isReleasing()) {
      $can_release = false;
    }

    $can_edit = PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $lease,
      PhabricatorPolicyCapability::CAN_EDIT);

    $curtain->addAction(
      id(new PhabricatorActionView())
        ->setName(pht('Release Lease'))
        ->setIcon('fa-times')
        ->setHref($this->getApplicationURI("/lease/{$id}/release/"))
        ->setWorkflow(true)
        ->setDisabled(!$can_release || !$can_edit));

    return $curtain;
  }

  private function buildPropertyListView(
    DrydockLease $lease) {
    $viewer = $this->getViewer();

    $view = new PHUIPropertyListView();

    $view->addProperty(
      pht('Resource Type'),
      $lease->getResourceType());

    $owner_phid = $lease->getOwnerPHID();
    if ($owner_phid) {
      $owner_display = $viewer->renderHandle($owner_phid);
    } else {
      $owner_display = phutil_tag('em', array(), pht('No Owner'));
    }
    $view->addProperty(pht('Owner'), $owner_display);

    $authorizing_phid = $lease->getAuthorizingPHID();
    if ($authorizing_phid) {
      $authorizing_display = $viewer->renderHandle($authorizing_phid);
    } else {
      $authorizing_display = phutil_tag('em', array(), pht('None'));
    }
    $view->addProperty(pht('Authorized By'), $authorizing_display);

    $resource_phid = $lease->getResourcePHID();
    if ($resource_phid) {
      $resource_display = $viewer->renderHandle($resource_phid);
    } else {
      $resource_display = phutil_tag('em', array(), pht('No Resource'));
    }
    $view->addProperty(pht('Resource'), $resource_display);

    $until = $lease->getUntil();
    if ($until) {
      $until_display = vixon_datetime($until, $viewer);
    } else {
      $until_display = phutil_tag('em', array(), pht('Never'));
    }
    $view->addProperty(pht('Expires'), $until_display);

    $acquired_epoch = $lease->getAcquiredEpoch();
    $activated_epoch = $lease->getActivatedEpoch();

    if ($acquired_epoch) {
      $acquired_display = vixon_datetime($acquired_epoch, $viewer);
    } else {
      if ($activated_epoch) {
        $acquired_display = phutil_tag(
          'em',
          array(),
          pht('Activated on Acquisition'));
      } else {
        $acquired_display = phutil_tag('em', array(), pht('Not Acquired'));
      }
    }
    $view->addProperty(pht('Acquired'), $acquired_display);

    if ($activated_epoch) {
      $activated_display = vixon_datetime($activated_epoch, $viewer);
    } else {
      $activated_display = phutil_tag('em', array(), pht('Not Activated'));
    }
    $view->addProperty(pht('Activated'), $activated_display);

    $attributes = $lease->getAttributes();
    if ($attributes) {
      $view->addSectionHeader(
        pht('Attributes'), 'fa-list-ul');
      foreach ($attributes as $key => $value) {
        $view->addProperty($key, $value);
      }
    }

    return $view;
  }

}
