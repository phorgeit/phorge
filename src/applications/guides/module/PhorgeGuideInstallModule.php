<?php

final class PhorgeGuideInstallModule extends PhorgeGuideModule {

  public function getModuleKey() {
    return 'install';
  }

  public function getModuleName() {
    return pht('Install');
  }

  public function getModulePosition() {
    return 20;
  }

  public function getIsModuleEnabled() {
    if (PhorgeEnv::getEnvConfig('cluster.instance')) {
      return false;
    }
    return true;
  }

  public function renderModuleStatus(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $guide_items = new PhorgeGuideListView();

    $title = pht('Resolve Setup Issues');
    $issues_resolved = !PhorgeSetupCheck::getOpenSetupIssueKeys();
    $href = PhorgeEnv::getURI('/config/issue/');
    if ($issues_resolved) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've resolved (or ignored) all outstanding setup issues.");
    } else {
      $icon = 'fa-warning';
      $icon_bg = 'bg-red';
      $description =
        pht('You have some unresolved setup issues to take care of.');
    }

    $item = id(new PhorgeGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);

    $configs = id(new PhorgeAuthProviderConfigQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->execute();

    $title = pht('Login and Registration');
    $href = PhorgeEnv::getURI('/auth/');
    $have_auth = (bool)$configs;
    if ($have_auth) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've configured at least one authentication provider.");
    } else {
      $icon = 'fa-key';
      $icon_bg = 'bg-sky';
      $description = pht(
        'Authentication providers allow users to register accounts and '.
        'log in.');
    }

    $item = id(new PhorgeGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('Configure');
    $href = PhorgeEnv::getURI('/config/');

    // Just load any config value at all; if one exists the install has figured
    // out how to configure things.
    $have_config = (bool)id(new PhorgeConfigEntry())->loadAllWhere(
      '1 = 1 LIMIT 1');

    if ($have_config) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've configured at least one setting from the web interface.");
    } else {
      $icon = 'fa-sliders';
      $icon_bg = 'bg-sky';
      $description = pht(
        'Learn how to configure mail and other options.');
    }

    $item = id(new PhorgeGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('User Account Settings');
    $href = PhorgeEnv::getURI('/settings/');
    $preferences = id(new PhorgeUserPreferencesQuery())
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

    $item = id(new PhorgeGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);
    $guide_items->addItem($item);


    $title = pht('Notification Server');
    $href = PhorgeEnv::getURI('/config/edit/notification.servers/');
    $have_notifications = PhorgeEnv::getEnvConfig('notification.servers');
    if ($have_notifications) {
      $icon = 'fa-check';
      $icon_bg = 'bg-green';
      $description = pht(
        "You've set up a real-time notification server.");
    } else {
      $icon = 'fa-bell';
      $icon_bg = 'bg-sky';
      $description = pht(
        'Real-time notifications can be delivered with WebSockets.');
    }

    $item = id(new PhorgeGuideItemView())
      ->setTitle($title)
      ->setHref($href)
      ->setIcon($icon)
      ->setIconBackground($icon_bg)
      ->setDescription($description);

    $guide_items->addItem($item);

    $intro = pht(
      '%s has been successfully installed. These next guides will '.
      'take you through configuration and new user orientation. '.
      'These steps are optional, and you can go through them in any order. '.
      'If you want to get back to this guide later on, you can find it in '.
      '{icon globe} **Applications** under {icon map-o} **Guides**.',
      PlatformSymbols::getPlatformServerName());

    $intro = new PHUIRemarkupView($viewer, $intro);

    $intro = id(new PHUIDocumentView())
      ->appendChild($intro);

    return array($intro, $guide_items);

  }

}
