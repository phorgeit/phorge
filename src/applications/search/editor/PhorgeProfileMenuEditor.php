<?php

final class PhorgeProfileMenuEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeSearchApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Profile Menu Items');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] =
      PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY;
    $types[] =
      PhorgeProfileMenuItemConfigurationTransaction::TYPE_ORDER;
    $types[] =
      PhorgeProfileMenuItemConfigurationTransaction::TYPE_VISIBILITY;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY:
        $key = $xaction->getMetadataValue('property.key');
        return $object->getMenuItemProperty($key, null);
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_ORDER:
        return $object->getMenuItemOrder();
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_VISIBILITY:
        return $object->getVisibility();
    }
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY:
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_VISIBILITY:
        return $xaction->getNewValue();
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_ORDER:
        return (int)$xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY:
        $key = $xaction->getMetadataValue('property.key');
        $value = $xaction->getNewValue();
        $object->setMenuItemProperty($key, $value);
        return;
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_ORDER:
        $object->setMenuItemOrder($xaction->getNewValue());
        return;
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_VISIBILITY:
        $object->setVisibility($xaction->getNewValue());
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY:
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_ORDER:
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_VISIBILITY:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    $actor = $this->getActor();
    $menu_item = $object->getMenuItem();
    $menu_item->setViewer($actor);

    switch ($type) {
      case PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY:
        $key_map = array();
        foreach ($xactions as $xaction) {
          $xaction_key = $xaction->getMetadataValue('property.key');
          $old = $this->getCustomTransactionOldValue($object, $xaction);
          $new = $xaction->getNewValue();
          $key_map[$xaction_key][] = array(
            'xaction' => $xaction,
            'old' => $old,
            'new' => $new,
          );
        }

        foreach ($object->validateTransactions($key_map) as $error) {
          $errors[] = $error;
        }
        break;
    }

    return $errors;
  }

  protected function supportsSearch() {
    return true;
  }

}
