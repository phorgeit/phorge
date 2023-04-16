<?php

final class PhorgePeopleProfileViewController
  extends PhorgePeopleProfileController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $username = $request->getURIData('username');

    $user = id(new PhorgePeopleQuery())
      ->setViewer($viewer)
      ->withUsernames(array($username))
      ->needProfileImage(true)
      ->needAvailability(true)
      ->executeOne();
    if (!$user) {
      return new Aphront404Response();
    }

    $this->setUser($user);
    $header = $this->buildProfileHeader();

    $properties = $this->buildPropertyView($user);
    $name = $user->getUsername();

    $feed = $this->buildPeopleFeed($user, $viewer);

    $view_all = id(new PHUIButtonView())
      ->setTag('a')
      ->setIcon(
        id(new PHUIIconView())
          ->setIcon('fa-list-ul'))
      ->setText(pht('View All'))
      ->setHref('/feed/?userPHIDs='.$user->getPHID());

    $feed_header = id(new PHUIHeaderView())
      ->setHeader(pht('Recent Activity'))
      ->addActionLink($view_all);

    $feed = id(new PHUIObjectBoxView())
      ->setHeader($feed_header)
      ->addClass('project-view-feed')
      ->appendChild($feed);

    $projects = $this->buildProjectsView($user);
    $calendar = $this->buildCalendarDayView($user);

    $home = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->addClass('project-view-home')
      ->addClass('project-view-people-home')
      ->setMainColumn(
        array(
          $properties,
          $feed,
        ))
      ->setSideColumn(
        array(
          $projects,
          $calendar,
        ));

    $navigation = $this->newNavigation(
      $user,
      PhorgePeopleProfileMenuEngine::ITEM_PROFILE);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->setBorder(true);

    return $this->newPage()
      ->setTitle($user->getUsername())
      ->setNavigation($navigation)
      ->setCrumbs($crumbs)
      ->setPageObjectPHIDs(
        array(
          $user->getPHID(),
        ))
      ->appendChild(
        array(
          $home,
        ));
  }

  private function buildPropertyView(
    PhorgeUser $user) {

    $viewer = $this->getRequest()->getUser();
    $view = id(new PHUIPropertyListView())
      ->setUser($viewer)
      ->setObject($user);

    $field_list = PhorgeCustomField::getObjectFields(
      $user,
      PhorgeCustomField::ROLE_VIEW);
    $field_list->appendFieldsToPropertyList($user, $viewer, $view);

    if (!$view->hasAnyProperties()) {
      return null;
    }

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('User Details'));

    $view = id(new PHUIObjectBoxView())
      ->appendChild($view)
      ->setHeader($header)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->addClass('project-view-properties');

    return $view;
  }

  private function buildProjectsView(
    PhorgeUser $user) {

    $viewer = $this->getViewer();
    $projects = id(new PhorgeProjectQuery())
      ->setViewer($viewer)
      ->withMemberPHIDs(array($user->getPHID()))
      ->needImages(true)
      ->withStatuses(
        array(
          PhorgeProjectStatus::STATUS_ACTIVE,
        ))
      ->execute();

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('Projects'));

    if (!empty($projects)) {
      $limit = 5;
      $render_phids = array_slice($projects, 0, $limit);
      $list = id(new PhorgeProjectListView())
        ->setUser($viewer)
        ->setProjects($render_phids);

      if (count($projects) > $limit) {
        $header_text = pht(
          'Projects (%s)',
          phutil_count($projects));

        $header = id(new PHUIHeaderView())
          ->setHeader($header_text)
          ->addActionLink(
            id(new PHUIButtonView())
              ->setTag('a')
              ->setIcon('fa-list-ul')
              ->setText(pht('View All'))
              ->setHref('/project/?member='.$user->getPHID()));

      }

    } else {
      $list = id(new PHUIInfoView())
        ->setSeverity(PHUIInfoView::SEVERITY_NODATA)
        ->appendChild(pht('User does not belong to any projects.'));
    }

    $box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($list)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY);

    return $box;
  }

  private function buildCalendarDayView(PhorgeUser $user) {
    $viewer = $this->getViewer();
    $class = 'PhorgeCalendarApplication';

    if (!PhorgeApplication::isClassInstalledForViewer($class, $viewer)) {
      return null;
    }

    // Don't show calendar information for disabled users, since it's probably
    // not useful or accurate and may be misleading.
    if ($user->getIsDisabled()) {
      return null;
    }

    $midnight = PhorgeTime::getTodayMidnightDateTime($viewer);
    $week_end = clone $midnight;
    $week_end = $week_end->modify('+3 days');

    $range_start = $midnight->format('U');
    $range_end = $week_end->format('U');

    $events = id(new PhorgeCalendarEventQuery())
      ->setViewer($viewer)
      ->withDateRange($range_start, $range_end)
      ->withInvitedPHIDs(array($user->getPHID()))
      ->withIsCancelled(false)
      ->needRSVPs(array($viewer->getPHID()))
      ->execute();

    $event_views = array();
    foreach ($events as $event) {
      $viewer_is_invited = $event->isRSVPInvited($viewer->getPHID());

      $can_edit = PhorgePolicyFilter::hasCapability(
        $viewer,
        $event,
        PhorgePolicyCapability::CAN_EDIT);

      $epoch_min = $event->getStartDateTimeEpoch();
      $epoch_max = $event->getEndDateTimeEpoch();

      $event_view = id(new AphrontCalendarEventView())
        ->setCanEdit($can_edit)
        ->setEventID($event->getID())
        ->setEpochRange($epoch_min, $epoch_max)
        ->setIsAllDay($event->getIsAllDay())
        ->setIcon($event->getIcon())
        ->setViewerIsInvited($viewer_is_invited)
        ->setName($event->getName())
        ->setDatetimeSummary($event->renderEventDate($viewer, true))
        ->setURI($event->getURI());

      $event_views[] = $event_view;
    }

    $event_views = msort($event_views, 'getEpochStart');

    $day_view = id(new PHUICalendarWeekView())
      ->setViewer($viewer)
      ->setView('week')
      ->setEvents($event_views)
      ->setWeekLength(3)
      ->render();

    $header = id(new PHUIHeaderView())
      ->setHeader(pht('Calendar'))
      ->setHref(
        urisprintf(
          '/calendar/?invited=%s#R',
          $user->getUsername()));

    $box = id(new PHUIObjectBoxView())
      ->setHeader($header)
      ->appendChild($day_view)
      ->addClass('calendar-profile-box')
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY);

    return $box;
  }

  private function buildPeopleFeed(
    PhorgeUser $user,
    $viewer) {

    $query = id(new PhorgeFeedQuery())
      ->setViewer($viewer)
      ->withFilterPHIDs(array($user->getPHID()))
      ->setLimit(100)
      ->setReturnPartialResultsOnOverheat(true);

    $stories = $query->execute();

    $overheated_view = null;
    $is_overheated = $query->getIsOverheated();
    if ($is_overheated) {
      $overheated_message =
        PhorgeApplicationSearchController::newOverheatedError(
          (bool)$stories);

      $overheated_view = id(new PHUIInfoView())
        ->setSeverity(PHUIInfoView::SEVERITY_WARNING)
        ->setTitle(pht('Query Overheated'))
        ->setErrors(
          array(
            $overheated_message,
          ));
    }

    $builder = new PhorgeFeedBuilder($stories);
    $builder->setUser($viewer);
    $builder->setShowHovercards(true);
    $builder->setNoDataString(pht('To begin on such a grand journey, '.
      'requires but just a single step.'));
    $view = $builder->buildView();

    return array(
      $overheated_view,
      $view->render(),
    );
  }

}
