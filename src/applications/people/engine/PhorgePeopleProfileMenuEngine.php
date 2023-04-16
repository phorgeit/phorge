<?php

final class PhorgePeopleProfileMenuEngine
  extends PhorgeProfileMenuEngine {

  const ITEM_PROFILE = 'people.profile';
  const ITEM_MANAGE = 'people.manage';
  const ITEM_PICTURE = 'people.picture';
  const ITEM_BADGES = 'people.badges';
  const ITEM_TASKS = 'people.tasks';
  const ITEM_COMMITS = 'people.commits';
  const ITEM_REVISIONS = 'people.revisions';

  protected function isMenuEngineConfigurable() {
    return false;
  }

  public function getItemURI($path) {
    $user = $this->getProfileObject();
    $username = $user->getUsername();
    $username = phutil_escape_uri($username);
    return "/p/{$username}/item/{$path}";
  }

  protected function getBuiltinProfileItems($object) {
    $viewer = $this->getViewer();

    $items = array();

    $items[] = $this->newItem()
      ->setBuiltinKey(self::ITEM_PICTURE)
      ->setMenuItemKey(PhorgePeoplePictureProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(self::ITEM_PROFILE)
      ->setMenuItemKey(PhorgePeopleDetailsProfileMenuItem::MENUITEMKEY);

    $have_badges = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeBadgesApplication',
      $viewer);
    if ($have_badges) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_BADGES)
        ->setMenuItemKey(PhorgePeopleBadgesProfileMenuItem::MENUITEMKEY);
    }

    $have_maniphest = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeManiphestApplication',
      $viewer);
    if ($have_maniphest) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_TASKS)
        ->setMenuItemKey(PhorgePeopleTasksProfileMenuItem::MENUITEMKEY);
    }

    $have_differential = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDifferentialApplication',
      $viewer);
    if ($have_differential) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_REVISIONS)
        ->setMenuItemKey(
          PhorgePeopleRevisionsProfileMenuItem::MENUITEMKEY);
    }

    $have_diffusion = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDiffusionApplication',
      $viewer);
    if ($have_diffusion) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_COMMITS)
        ->setMenuItemKey(PhorgePeopleCommitsProfileMenuItem::MENUITEMKEY);
    }

    $items[] = $this->newItem()
      ->setBuiltinKey(self::ITEM_MANAGE)
      ->setMenuItemKey(PhorgePeopleManageProfileMenuItem::MENUITEMKEY);

    return $items;
  }

}
