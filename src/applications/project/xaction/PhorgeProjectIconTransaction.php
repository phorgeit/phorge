<?php

final class PhorgeProjectIconTransaction
  extends PhorgeProjectTransactionType {

  const TRANSACTIONTYPE = 'project:icon';

  public function generateOldValue($object) {
    return $object->getIcon();
  }

  public function applyInternalEffects($object, $value) {
    $object->setIcon($value);
  }

  public function getTitle() {
    $set = new PhorgeProjectIconSet();
    $new = $this->getNewValue();

    return pht(
      "%s set this project's icon to %s.",
      $this->renderAuthor(),
      $this->renderValue($set->getIconLabel($new)));
  }

  public function getTitleForFeed() {
    $set = new PhorgeProjectIconSet();
    $new = $this->getNewValue();

    return pht(
      '%s set the icon for %s to %s.',
      $this->renderAuthor(),
      $this->renderObject(),
      $this->renderValue($set->getIconLabel($new)));
  }

  public function getIcon() {
    $new = $this->getNewValue();
    return PhorgeProjectIconSet::getIconIcon($new);
  }

}
