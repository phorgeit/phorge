<?php

final class PhorgePeoplePictureProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'people.picture';

  public function getMenuItemTypeName() {
    return pht('User Picture');
  }

  private function getDefaultName() {
    return pht('User Picture');
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

    $user = $config->getProfileObject();

    $picture = $user->getProfileImageURI();
    $name = $user->getUsername();

    $item = $this->newItemView()
      ->setDisabled($user->getIsDisabled());

    $item->newProfileImage($picture);

    return array(
      $item,
    );
  }

}
