<?php

final class PhortuneAccountEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Accounts');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this payment account.', $author);
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

    $viewer = $this->requireActor();

    switch ($type) {
      case PhorgeTransactions::TYPE_EDGE:
        foreach ($xactions as $xaction) {
          switch ($xaction->getMetadataValue('edge:type')) {
            case PhortuneAccountHasMemberEdgeType::EDGECONST:
              $old = $object->getMemberPHIDs();
              $new = $this->getPHIDTransactionNewValue($xaction, $old);

              $old = array_fuse($old);
              $new = array_fuse($new);

              foreach ($new as $new_phid) {
                if (isset($old[$new_phid])) {
                  continue;
                }

                $user = id(new PhorgePeopleQuery())
                  ->setViewer($viewer)
                  ->withPHIDs(array($new_phid))
                  ->executeOne();
                if (!$user) {
                  $error = new PhorgeApplicationTransactionValidationError(
                    $type,
                    pht('Invalid'),
                    pht(
                      'Account managers must be valid users, "%s" is not.',
                      $new_phid));
                  $errors[] = $error;
                  continue;
                }
              }

              $actor_phid = $this->getActingAsPHID();
              if (isset($old[$actor_phid]) && !isset($new[$actor_phid])) {
                $error = new PhorgeApplicationTransactionValidationError(
                  $type,
                  pht('Invalid'),
                  pht('You can not remove yourself as an account manager.'),
                  $xaction);
                $errors[] = $error;
              }
            break;
          }
        }
        break;
    }
    return $errors;
  }

}
