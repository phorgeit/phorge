<?php

final class PhabricatorPeopleRevisionsProfileMenuItem
  extends PhabricatorProfileMenuItem {

  const MENUITEMKEY = 'people.revisions';

  public function getMenuItemTypeName() {
    return pht('Authored Revisions');
  }

  private function getDefaultName() {
    return pht('Authored Revisions');
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
      ->setURI("/people/revisions/{$id}/")
      ->setName($this->getDisplayName($config))
      ->setIcon('fa-gear');

    return array(
      $item,
    );
  }

}
