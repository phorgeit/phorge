<?php

final class PhabricatorProjectColorTransaction
  extends PhabricatorProjectTransactionType {

  const TRANSACTIONTYPE = 'project:color';

  public function generateOldValue($object) {
    return $object->getColor();
  }

  public function applyInternalEffects($object, $value) {
    $object->setColor($value);
  }

  public function getTitle() {
    $new = $this->getNewValue();
    return pht(
      "%s set this project's color to %s.",
      $this->renderAuthor(),
      $this->renderValue(PHUITagView::getShadeName($new)));
  }

  public function getTitleForFeed() {
    $new = $this->getNewValue();
    return pht(
      '%s set the color for %s to %s.',
      $this->renderAuthor(),
      $this->renderObject(),
      $this->renderValue(PHUITagView::getShadeName($new)));
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    if (!$xactions) {
      return $errors;
    }

    foreach ($xactions as $xaction) {
      $new_color = $xaction->getNewValue();
      if (!PhabricatorProjectIconSet::getColorName($new_color)) {
        $errors[] = new PhabricatorApplicationTransactionValidationError(
          self::TRANSACTIONTYPE,
          pht('Invalid'),
          pht(
            'Value for "%s" is invalid: "%s".',
            self::TRANSACTIONTYPE,
            $new_color));
        break;
      }
    }

    return $errors;
  }

}
