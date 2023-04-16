<?php

final class PhorgePeopleDetailsProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'people.details';

  public function getMenuItemTypeName() {
    return pht('User Details');
  }

  private function getDefaultName() {
    return pht('User Details');
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
        ->setValue($config->getMenuProperty('name')),
    );
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $user = $config->getProfileObject();
    $uri = urisprintf(
      '/p/%s/',
      $user->getUsername());

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName(pht('Profile'))
      ->setIcon('fa-user');

    return array(
      $item,
    );
  }

}
