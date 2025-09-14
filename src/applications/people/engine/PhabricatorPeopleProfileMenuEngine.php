<?php

final class PhabricatorPeopleProfileMenuEngine
  extends PhabricatorProfileMenuEngine {

  const ITEM_PROFILE = 'people.profile';
  const ITEM_MANAGE = 'people.manage';
  const ITEM_PICTURE = 'people.picture';
  const ITEM_BADGES = 'people.badges';
  const ITEM_TASKS_ASSIGNED = 'people.tasks.assigned';
  const ITEM_TASKS_AUTHORED = 'people.tasks.authored';
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
      ->setMenuItemKey(PhabricatorPeoplePictureProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(self::ITEM_PROFILE)
      ->setMenuItemKey(PhabricatorPeopleDetailsProfileMenuItem::MENUITEMKEY);

    $have_maniphest = PhabricatorApplication::isClassInstalledForViewer(
      PhabricatorManiphestApplication::class,
      $viewer);
    if ($have_maniphest) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_TASKS_ASSIGNED)
        ->setMenuItemKey(
          PhabricatorPeopleTasksAssignedProfileMenuItem::MENUITEMKEY);
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_TASKS_AUTHORED)
        ->setMenuItemKey(
          PhabricatorPeopleTasksAuthoredProfileMenuItem::MENUITEMKEY);
    }

    $have_differential = PhabricatorApplication::isClassInstalledForViewer(
      PhabricatorDifferentialApplication::class,
      $viewer);
    if ($have_differential) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_REVISIONS)
        ->setMenuItemKey(
          PhabricatorPeopleRevisionsProfileMenuItem::MENUITEMKEY);
    }

    $have_diffusion = PhabricatorApplication::isClassInstalledForViewer(
      PhabricatorDiffusionApplication::class,
      $viewer);
    if ($have_diffusion) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_COMMITS)
        ->setMenuItemKey(PhabricatorPeopleCommitsProfileMenuItem::MENUITEMKEY);
    }

    $have_badges = PhabricatorApplication::isClassInstalledForViewer(
      PhabricatorBadgesApplication::class,
      $viewer);
    if ($have_badges) {
      $items[] = $this->newItem()
        ->setBuiltinKey(self::ITEM_BADGES)
        ->setMenuItemKey(PhabricatorPeopleBadgesProfileMenuItem::MENUITEMKEY);
    }

    $items[] = $this->newItem()
      ->setBuiltinKey(self::ITEM_MANAGE)
      ->setMenuItemKey(PhabricatorPeopleManageProfileMenuItem::MENUITEMKEY);

    return $items;
  }

}
