<?php

final class PhorgeHomeLauncherProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'home.launcher.menu';

  public function getMenuItemTypeName() {
    return pht('More Applications');
  }

  private function getDefaultName() {
    return pht('More Applications');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-ellipsis-h';
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function canMakeDefault(
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
    $viewer = $this->getViewer();

    $name = $this->getDisplayName($config);
    $icon = 'fa-ellipsis-h';
    $uri = '/applications/';

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
