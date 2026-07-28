<?php

final class PhorgePolicyNamedPolicyEffectivePolicyTransaction
  extends PhorgePolicyNamedPolicyTransactionType {

  const TRANSACTIONTYPE = 'namedpolicy:effectivepolicy';

  public function generateOldValue($object) {
    return $object->getEffectivePolicy();
  }

  public function applyInternalEffects($object, $value) {
    $object->setEffectivePolicy($value);
  }



  public function validateTransactions($object, array $xactions) {
    $errors = array();

    if ($this->isEmptyTextTransaction($object->getName(), $xactions)) {
      $errors[] = $this->newRequiredError(
        pht('Effective Policy is required.'));
    }

    foreach ($xactions as $xaction) {
      $new = $xaction->getNewValue();
      if (phid_get_type($new) == PhorgePolicyPHIDTypeNamedPolicy::TYPECONST) {
        $errors[] = $this->newInvalidError(
          pht(
            'A %s cannot be used as an effective policy for a %s',
            $this->renderObjectType(),
            $this->renderObjectType()),
          $xaction);

        continue;
      }
    }

    return $errors;
  }

  public function getTitle() {
    $old = $this->getOldValue();
    if ($old == null) {
      return pht(
        '%s set the effective policy to %s.',
        $this->renderAuthor(),
        $this->renderNewPolicy());
    } else {
      return pht(
        '%s changed the effective policy from %s to %s.',
        $this->renderAuthor(),
        $this->renderOldPolicy(),
        $this->renderNewPolicy());
    }
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    if ($old == null) {
      return pht(
        '%s set the effective policy of %s to %s',
        $this->renderAuthor(),
        $this->renderObject(),
        $this->renderNewPolicy());
    } else {
      return pht(
        '%s changed the effective policy of %s from %s to %s',
        $this->renderAuthor(),
        $this->renderObject(),
        $this->renderOldPolicy(),
        $this->renderNewPolicy());

    }
  }

}
