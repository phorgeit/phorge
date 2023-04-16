<?php

final class PhorgeDividerProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'divider';

  public function getMenuItemTypeIcon() {
    return 'fa-minus';
  }

  public function getMenuItemTypeName() {
    return pht('Divider');
  }

  public function canAddToObject($object) {
    return true;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    return pht("\xE2\x80\x94");
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeInstructionsEditField())
        ->setValue(
          pht(
            'This is a visual divider which you can use to separate '.
            'sections in the menu. It does not have any configurable '.
            'options.')),
    );
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $item = $this->newItemView()
      ->setIsDivider(true);

    return array(
      $item,
    );
  }

}
