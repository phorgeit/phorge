<?php

final class PhorgeProjectSubprojectsProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.subprojects';

  public function getMenuItemTypeName() {
    return pht('Project Subprojects');
  }

  private function getDefaultName() {
    return pht('Subprojects');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-sitemap';
  }

  public function shouldEnableForObject($object) {
    if ($object->isMilestone()) {
      return false;
    }

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
    $icon = 'fa-sitemap';
    $uri = "/project/subprojects/{$id}/";

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon);

    return array(
      $item,
    );
  }

}
