<?php

final class PhorgeProjectManageProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.manage';

  public function getMenuItemTypeName() {
    return pht('Manage Project');
  }

  private function getDefaultName() {
    return pht('Manage');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-cog';
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
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

    $project = $config->getProfileObject();

    $id = $project->getID();

    $name = $this->getDisplayName($config);
    $icon = 'fa-gears';
    $uri = "/project/manage/{$id}/";

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
