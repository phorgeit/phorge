<?php

final class PhabricatorPeopleTasksAssignedProfileMenuItem
  extends PhabricatorProfileMenuItem {

  const MENUITEMKEY = 'people.tasks.assigned';

  public function getMenuItemTypeName() {
    return pht('Assigned Tasks');
  }

  private function getDefaultName() {
    return pht('Assigned Tasks');
  }

  public function canHideMenuItem(
    PhabricatorProfileMenuItemConfiguration $config) {
    return true;
  }

  public function getDisplayName(
    PhabricatorProfileMenuItemConfiguration $config) {
    $name = $config->getMenuItemProperty('name');

    if (phutil_nonempty_string($name)) {
      return $name;
    }

    return $this->getDefaultName();
  }

  public function buildEditEngineFields(
    PhabricatorProfileMenuItemConfiguration $config) {
    return array(
      id(new PhabricatorTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setPlaceholder($this->getDefaultName())
        ->setValue($config->getMenuItemProperty('name')),
    );
  }

  protected function newMenuItemViewList(
    PhabricatorProfileMenuItemConfiguration $config) {

    $user = $config->getProfileObject();
    $id = $user->getID();

    $item = $this->newItemView()
      ->setURI("/people/tasks/assigned/{$id}/")
      ->setName($this->getDisplayName($config))
      ->setIcon('fa-anchor');

    return array(
      $item,
    );
  }

}
