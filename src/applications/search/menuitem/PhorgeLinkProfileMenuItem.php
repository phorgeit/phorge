<?php

final class PhorgeLinkProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'link';

  const FIELD_URI = 'uri';
  const FIELD_NAME = 'name';
  const FIELD_TOOLTIP = 'tooltip';

  public function getMenuItemTypeIcon() {
    return 'fa-link';
  }

  public function getMenuItemTypeName() {
    return pht('Link');
  }

  public function canAddToObject($object) {
    return true;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $this->getLinkName($config);
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey(self::FIELD_NAME)
        ->setLabel(pht('Name'))
        ->setIsRequired(true)
        ->setValue($this->getLinkName($config)),
      id(new PhorgeTextEditField())
        ->setKey(self::FIELD_URI)
        ->setLabel(pht('URI'))
        ->setIsRequired(true)
        ->setValue($this->getLinkURI($config)),
      id(new PhorgeTextEditField())
        ->setKey(self::FIELD_TOOLTIP)
        ->setLabel(pht('Tooltip'))
        ->setValue($this->getLinkTooltip($config)),
      id(new PhorgeIconSetEditField())
        ->setKey('icon')
        ->setLabel(pht('Icon'))
        ->setIconSet(new PhorgeProfileMenuItemIconSet())
        ->setValue($this->getLinkIcon($config)),
    );
  }

  private function getLinkName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('name');
  }

  private function getLinkIcon(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('icon', 'link');
  }

  private function getLinkURI(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('uri');
  }

  private function getLinkTooltip(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('tooltip');
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $icon = $this->getLinkIcon($config);
    $name = $this->getLinkName($config);
    $uri = $this->getLinkURI($config);
    $tooltip = $this->getLinkTooltip($config);

    $icon_object = id(new PhorgeProfileMenuItemIconSet())
      ->getIcon($icon);
    if ($icon_object) {
      $icon_class = $icon_object->getIcon();
    } else {
      $icon_class = 'fa-link';
    }

    $item = $this->newItemView()
      ->setURI($uri)
      ->setName($name)
      ->setIcon($icon_class)
      ->setTooltip($tooltip)
      ->setIsExternalLink(true);

    return array(
      $item,
    );
  }

  public function validateTransactions(
    PhorgeProfileMenuItemConfiguration $config,
    $field_key,
    $value,
    array $xactions) {

    $viewer = $this->getViewer();
    $errors = array();

    if ($field_key == self::FIELD_NAME) {
      if ($this->isEmptyTransaction($value, $xactions)) {
       $errors[] = $this->newRequiredError(
         pht('You must choose a link name.'),
         $field_key);
      }
    }

    if ($field_key == self::FIELD_URI) {
      if ($this->isEmptyTransaction($value, $xactions)) {
       $errors[] = $this->newRequiredError(
         pht('You must choose a URI to link to.'),
         $field_key);
      }

      foreach ($xactions as $xaction) {
        $new = $xaction['new'];

        if (!$new) {
          continue;
        }

        if ($new === $value) {
          continue;
        }

        if (!PhorgeEnv::isValidURIForLink($new)) {
          $errors[] = $this->newInvalidError(
            pht(
              'URI "%s" is not a valid link URI. It should be a full, valid '.
              'URI beginning with a protocol like "%s".',
              $new,
              'https://'),
            $xaction['xaction']);
        }
      }
    }

    return $errors;
  }
}
