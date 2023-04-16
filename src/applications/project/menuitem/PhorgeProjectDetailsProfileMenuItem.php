<?php

final class PhorgeProjectDetailsProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.details';

  public function getMenuItemTypeName() {
    return pht('Project Details');
  }

  private function getDefaultName() {
    return pht('Project Details');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-file-text-o';
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
    $name = $project->getName();
    $icon = $project->getDisplayIconIcon();

    $uri = "/project/profile/{$id}/";

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
