<?php

final class PhorgeManageProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'menu.manage';

  public function getMenuItemTypeName() {
    return pht('Manage Menu');
  }

  private function getDefaultName() {
    return pht('Edit Menu');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-pencil';
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

    if (!$viewer->isLoggedIn()) {
      return array();
    }

    $engine = $this->getEngine();
    $uri = $engine->getItemURI('configure/');

    $name = $this->getDisplayName($config);
    $icon = 'fa-pencil';

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
