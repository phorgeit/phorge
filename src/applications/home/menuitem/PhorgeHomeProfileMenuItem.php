<?php

final class PhorgeHomeProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'home.dashboard';

  public function getMenuItemTypeName() {
    return pht('Built-in Homepage');
  }

  private function getDefaultName() {
    return pht('Home');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-home';
  }

  public function canMakeDefault(
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

  public function newPageContent(
    PhorgeProfileMenuItemConfiguration $config) {
    $viewer = $this->getViewer();

    return id(new PHUIHomeView())
      ->setViewer($viewer);
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
    $icon = 'fa-home';
    $uri = $this->getItemViewURI($config);

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
