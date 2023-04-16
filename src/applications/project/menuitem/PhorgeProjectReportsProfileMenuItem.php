<?php

final class PhorgeProjectReportsProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'project.reports';

  public function getMenuItemTypeName() {
    return pht('Project Reports');
  }

  private function getDefaultName() {
    return pht('Reports (Prototype)');
  }

  public function getMenuItemTypeIcon() {
    return 'fa-area-chart';
  }

  public function canMakeDefault(
    PhorgeProfileMenuItemConfiguration $config) {
    return true;
  }

  public function shouldEnableForObject($object) {
    $viewer = $this->getViewer();

    if (!PhorgeEnv::getEnvConfig('phorge.show-prototypes')) {
      return false;
    }

    $class = 'PhorgeManiphestApplication';
    if (!PhorgeApplication::isClassInstalledForViewer($class, $viewer)) {
      return false;
    }

    $class = 'PhorgeFactApplication';
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
    $uri = $project->getReportsURI();
    $name = $this->getDisplayName($config);

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon('fa-area-chart');

    return array(
      $item,
    );
  }

}
