<?php

final class PhorgeProjectMembersViewController
  extends PhorgeProjectController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $project = id(new PhorgeProjectQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needMembers(true)
      ->needWatchers(true)
      ->needImages(true)
      ->executeOne();
    if (!$project) {
      return new Aphront404Response();
    }

    $this->setProject($project);
    $title = pht('Members and Watchers');
    $curtain = $this->buildCurtainView($project);

    $member_list = id(new PhorgeProjectMemberListView())
      ->setUser($viewer)
      ->setProject($project)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->setUserPHIDs($project->getMemberPHIDs())
      ->setShowNote(true);

    $watcher_list = id(new PhorgeProjectWatcherListView())
      ->setUser($viewer)
      ->setProject($project)
      ->setBackground(PHUIObjectBoxView::BLUE_PROPERTY)
      ->setUserPHIDs($project->getWatcherPHIDs())
      ->setShowNote(true);

    $nav = $this->newNavigation(
      $project,
      PhorgeProject::ITEM_MEMBERS);

    $crumbs = $this->buildApplicationCrumbs();
    $crumbs->addTextCrumb(pht('Members'));
    $crumbs->setBorder(true);

    $header = id(new PHUIHeaderView())
      ->setHeader($title)
      ->setHeaderIcon('fa-group');

    require_celerity_resource('project-view-css');

    $view = id(new PHUITwoColumnView())
      ->setHeader($header)
      ->setCurtain($curtain)
      ->addClass('project-view-home')
      ->addClass('project-view-people-home')
      ->setMainColumn(array(
        $member_list,
        $watcher_list,
      ));

    return $this->newPage()
      ->setNavigation($nav)
      ->setCrumbs($crumbs)
      ->setTitle(array($project->getName(), $title))
      ->appendChild($view);
  }

  private function buildCurtainView(PhorgeProject $project) {
    $viewer = $this->getViewer();
    $id = $project->getID();

    $curtain = $this->newCurtainView();

    $is_locked = $project->getIsMembershipLocked();

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $project,
      PhorgePolicyCapability::CAN_EDIT);

    $supports_edit = $project->supportsEditMembers();

    $can_join = $supports_edit && PhorgePolicyFilter::hasCapability(
      $viewer,
      $project,
      PhorgePolicyCapability::CAN_JOIN);

    $can_leave = $supports_edit && (!$is_locked || $can_edit);

    $viewer_phid = $viewer->getPHID();

    if (!$project->isUserMember($viewer_phid)) {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setHref('/project/update/'.$project->getID().'/join/')
          ->setIcon('fa-plus')
          ->setDisabled(!$can_join)
          ->setWorkflow(true)
          ->setName(pht('Join Project')));
    } else {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setHref('/project/update/'.$project->getID().'/leave/')
          ->setIcon('fa-times')
          ->setDisabled(!$can_leave)
          ->setWorkflow(true)
          ->setName(pht('Leave Project')));
    }

    if (!$project->isUserWatcher($viewer->getPHID())) {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setWorkflow(true)
          ->setHref('/project/watch/'.$project->getID().'/')
          ->setIcon('fa-eye')
          ->setName(pht('Watch Project')));
    } else {
      $curtain->addAction(
        id(new PhorgeActionView())
          ->setWorkflow(true)
          ->setHref('/project/unwatch/'.$project->getID().'/')
          ->setIcon('fa-eye-slash')
          ->setName(pht('Unwatch Project')));
    }

    $can_silence = $project->isUserMember($viewer_phid);
    $is_silenced = $this->isProjectSilenced($project);

    if ($is_silenced) {
      $silence_text = pht('Enable Mail');
    } else {
      $silence_text = pht('Disable Mail');
    }

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName($silence_text)
        ->setIcon('fa-envelope-o')
        ->setHref("/project/silence/{$id}/")
        ->setWorkflow(true)
        ->setDisabled(!$can_silence));

    $can_add = $can_edit && $supports_edit;

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Add Members'))
        ->setIcon('fa-user-plus')
        ->setHref("/project/members/{$id}/add/")
        ->setWorkflow(true)
        ->setDisabled(!$can_add));

    $can_lock = $can_edit && $supports_edit && $this->hasApplicationCapability(
      ProjectCanLockProjectsCapability::CAPABILITY);

    if ($is_locked) {
      $lock_name = pht('Unlock Project');
      $lock_icon = 'fa-unlock';
    } else {
      $lock_name = pht('Lock Project');
      $lock_icon = 'fa-lock';
    }

    $curtain->addAction(
      id(new PhorgeActionView())
        ->setName($lock_name)
        ->setIcon($lock_icon)
        ->setHref($this->getApplicationURI("lock/{$id}/"))
        ->setDisabled(!$can_lock)
        ->setWorkflow(true));

    if ($project->isMilestone()) {
      $icon_key = PhorgeProjectIconSet::getMilestoneIconKey();
      $header = PhorgeProjectIconSet::getIconName($icon_key);
      $note = pht(
        'Members of the parent project are members of this project.');
      $show_join = false;
    } else if ($project->getHasSubprojects()) {
      $header = pht('Parent Project');
      $note = pht(
        'Members of all subprojects are members of this project.');
      $show_join = false;
    } else if ($project->getIsMembershipLocked()) {
      $header = pht('Locked Project');
      $note = pht(
        'Users with access may join this project, but may not leave.');
      $show_join = true;
    } else {
      $header = pht('Normal Project');
      $note = pht('Users with access may join and leave this project.');
      $show_join = true;
    }

    $curtain->newPanel()
      ->setHeaderText($header)
      ->appendChild($note);

    if ($show_join) {
      $descriptions = PhorgePolicyQuery::renderPolicyDescriptions(
        $viewer,
        $project);

      $curtain->newPanel()
        ->setHeaderText(pht('Joinable By'))
        ->appendChild($descriptions[PhorgePolicyCapability::CAN_JOIN]);
    }

    return $curtain;
  }

  private function isProjectSilenced(PhorgeProject $project) {
    $viewer = $this->getViewer();

    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return false;
    }

    $edge_type = PhorgeProjectSilencedEdgeType::EDGECONST;
    $silenced = PhorgeEdgeQuery::loadDestinationPHIDs(
      $project->getPHID(),
      $edge_type);
    $silenced = array_fuse($silenced);
    return isset($silenced[$viewer_phid]);
  }

}
