<?php

final class PhorgeProjectWorkboardProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.workboard';

  public function getMenuItemTypeName() {
    return pht('Project Workboard');
  }

  private function getDefaultName() {
    return pht('Workboard');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-columns';
  }

  public function canMakeDefault(
    PhorgeProfileMenuItemConfiguration $config) {
    return true;
  }

  public function shouldEnableForObject($object) {
    $viewer = $this->getViewer();

    // Workboards are only available if Maniphest is installed.
    $class = 'PhorgeManiphestApplication';
    if (!PhorgeApplication::isClassInstalledForViewer($class, $viewer)) {
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
    $uri = $project->getWorkboardURI();
    $name = $this->getDisplayName($config);

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon('fa-columns');

    return array(
      $item,
    );
  }

}
