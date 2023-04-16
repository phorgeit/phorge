<?php

final class PhorgePeopleTasksProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'people.tasks';

  public function getMenuItemTypeName() {
    return pht('Tasks');
  }

  private function getDefaultName() {
    return pht('Tasks');
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return true;
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
      ->setURI("/people/tasks/{$id}/")
      ->setName($this->getDisplayName($config))
      ->setIcon('fa-anchor');

    return array(
      $item,
    );
  }

}
