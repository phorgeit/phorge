<?php

final class PhabricatorGuideQuickStartModule extends PhabricatorGuideModule {

  public function getModuleKey() {
    return 'quickstart';
  }

  public function getModuleName() {
    return pht('Quick Start');
  }

  public function getModulePosition() {
    return 30;
  }

  public function getIsModuleEnabled() {
    return true;
  }

  public function renderModuleStatus(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $instance = PhabricatorEnv::getEnvConfig('cluster.instance');

    $guide_items = new PhabricatorGuideListView();

    $title = pht('Create a Repository');
    $repository_check = id(new PhabricatorRepositoryQuery())
      ->setViewer($viewer)
      ->execute();
    $href = PhabricatorEnv::getURI('/diffusion/');
    if ($repository_check) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've created at least one repository.");
    } else {
      $icon = 'fa-code';
      $icon_bg = 'bg-sky';
      $description =
        pht('If you are here for code review, let\'s set up your first '.
        'repository.');
    }

    $item = id(new PhabricatorGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('Create a Project');
    $project_check = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->execute();
    $href = PhabricatorEnv::getURI('/project/');
    if ($project_check) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've created at least one project.");
    } else {
      $icon = 'fa-briefcase';
      $icon_bg = 'bg-sky';
      $description =
        pht('Project tags define everything. Create them for teams, tags, '.
          'or actual projects.');
    }

    $item = id(new PhabricatorGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('Create a Task');
    $task_check = id(new ManiphestTaskQuery())
      ->setViewer($viewer)
      ->execute();
    $href = PhabricatorEnv::getURI('/maniphest/');
    if ($task_check) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've created at least one task.");
    } else {
      $icon = 'fa-anchor';
      $icon_bg = 'bg-sky';
      $description =
        pht('Create some work for the interns in Maniphest.');
    }

    $item = id(new PhabricatorGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);

    $title = pht('User Account Settings');
    $href = PhabricatorEnv::getURI('/settings/');
    $preferences = id(new PhabricatorUserPreferencesQuery())
      ->setViewer($viewer)
      ->withUsers(array($viewer))
      ->executeOne();

    $have_settings = ($preferences && $preferences->getPreferences());
    if ($have_settings) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've adjusted at least one setting on your account.");
    } else {
      $icon = 'fa-wrench';
      $icon_bg = 'bg-sky';
      $description = pht(
        'Configure account settings for all users, or just yourself');
    }

    $item = id(new PhabricatorGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('Explore Applications');
    $href = PhabricatorEnv::getURI('/applications/');
    $icon = 'fa-globe';
    $icon_bg = 'bg-sky';
    $description =
      pht('See all available applications.');

    $item = id(new PhabricatorGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);

    if (!$instance) {
      $title = pht('Invite Collaborators');
      $people_check = id(new PhabricatorPeopleQuery())
        ->setViewer($viewer)
        ->execute();
      $people = count($people_check);
      $href = PhabricatorEnv::getURI('/people/invite/send/');
      if ($people > 1) {
        $icon = 'fa-check';
        $icon_bg = 'bg-green';
        $description = pht(
          'Your invitations have been accepted. You will not be alone on '.
          'this journey.');
      } else {
        $icon = 'fa-group';
        $icon_bg = 'bg-sky';
        $description =
          pht('Invite the rest of your team to get started.');
      }

      $item = id(new PhabricatorGuideItemView())
        ->setTitle($title)
        ->setHref($href)
        ->setIcon($icon)
        ->setIconBackground($icon_bg)
        ->setDescription($description);
      $guide_items->addItem($item);
    }

    $intro = pht(
      'If you\'re new to %s, these optional steps can help you learn the '.
      'basics. Feel free to set things up for how you work best and explore '.
      'these features at your own pace.',
      PlatformSymbols::getPlatformServerName());

    $intro = new PHUIRemarkupView($viewer, $intro);
    $intro = id(new PHUIDocumentView())
      ->appendChild($intro);

    return array($intro, $guide_items);

  }

}
