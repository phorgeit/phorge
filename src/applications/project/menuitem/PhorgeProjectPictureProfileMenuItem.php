<?php

final class PhorgeProjectPictureProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.picture';

  public function getMenuItemTypeName() {
    return pht('Project Picture');
  }

  private function getDefaultName() {
    return pht('Project Picture');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-image';
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $this->getDefaultName();
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array();
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $project = $config->getProfileObject();
    $picture = $project->getProfileImageURI();

    $item = $this->newItemView()
      ->setDisabled($project->isArchived());

    $item->newProfileImage($picture);

    return array(
      $item,
    );
  }

}
