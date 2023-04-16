<?php

final class PhortuneMerchantEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Merchants');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this merchant.', $author);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDGE;

    return $types;
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
      case PhorgeTransactions::TYPE_EDGE:
        foreach ($xactions as $xaction) {
          switch ($xaction->getMetadataValue('edge:type')) {
            case PhortuneMerchantHasMemberEdgeType::EDGECONST:
              $new = $xaction->getNewValue();
              $set = idx($new, '-', array());
              $actor_phid = $this->requireActor()->getPHID();
              foreach ($set as $phid) {
                if ($actor_phid == $phid) {
                  $error = new PhorgeApplicationTransactionValidationError(
                    $type,
                    pht('Invalid'),
                    pht('You can not remove yourself as an merchant manager.'),
                    $xaction);
                  $errors[] = $error;
                }
              }
            break;
          }
        }
        break;
    }
    return $errors;
  }

}
