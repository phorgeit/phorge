<?php

final class PhorgeProjectProfileMenuEngine
  extends PhorgeProfileMenuEngine {

  protected function isMenuEngineConfigurable() {
    return true;
  }

  protected function isMenuEnginePersonalizable() {
    return false;
  }

  public function getItemURI($path) {
    $project = $this->getProfileObject();
    $id = $project->getID();
    return "/project/{$id}/item/{$path}";
  }

  protected function getBuiltinProfileItems($object) {
    $items = array();

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_PICTURE)
      ->setMenuItemKey(PhorgeProjectPictureProfileMenuItem::MENUITEMKEY)
      ->setIsHeadItem(true);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_PROFILE)
      ->setMenuItemKey(PhorgeProjectDetailsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_POINTS)
      ->setMenuItemKey(PhorgeProjectPointsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_WORKBOARD)
      ->setMenuItemKey(PhorgeProjectWorkboardProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_REPORTS)
      ->setMenuItemKey(PhorgeProjectReportsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_MEMBERS)
      ->setMenuItemKey(PhorgeProjectMembersProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_SUBPROJECTS)
      ->setMenuItemKey(
        PhorgeProjectSubprojectsProfileMenuItem::MENUITEMKEY);

    $items[] = $this->newItem()
      ->setBuiltinKey(PhorgeProject::ITEM_MANAGE)
      ->setMenuItemKey(PhorgeProjectManageProfileMenuItem::MENUITEMKEY)
      ->setIsTailItem(true);

    return $items;
  }

}
