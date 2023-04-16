<?php

final class PhorgePeopleManageProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'people.manage';

  public function getMenuItemTypeName() {
    return pht('Manage User');
  }

  private function getDefaultName() {
    return pht('Manage');
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    $name = $config->getMenuItemProperty('name');

    if (strlen($name)) {
      return $name;
    }

    return $this->getDefaultName();
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setPlaceholder($this->getDefaultName())
        ->setValue($config->getMenuItemProperty('name')),
    );
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $user = $config->getProfileObject();
    $id = $user->getID();

    $item = $this->newItemView()
      ->setURI("/people/manage/{$id}/")
      ->setName($this->getDisplayName($config))
      ->setIcon('fa-gears');

    return array(
      $item,
    );
  }

}
