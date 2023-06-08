<?php

final class PhabricatorRepositoryDefaultBranchTransaction
  extends PhabricatorRepositoryTransactionType {

  const TRANSACTIONTYPE = 'repo:default-branch';

  public function generateOldValue($object) {
    return $object->getDetail('default-branch');
  }

  public function applyInternalEffects($object, $value) {
    $object->setDetail('default-branch', $value);
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    if (!phutil_nonempty_string($new)) {
      return pht(
        '%s removed %s as the default branch.',
        $this->renderAuthor(),
        $this->renderOldValue());
    } else if (!phutil_nonempty_string($old)) {
      return pht(
        '%s set the default branch to %s.',
        $this->renderAuthor(),
        $this->renderNewValue());
    } else {
      return pht(
        '%s changed the default branch from %s to %s.',
        $this->renderAuthor(),
        $this->renderOldValue(),
        $this->renderNewValue());
    }
  }

}
