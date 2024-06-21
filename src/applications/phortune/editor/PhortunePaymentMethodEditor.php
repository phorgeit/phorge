<?php

final class PhortunePaymentMethodEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorPhortuneApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Payment Methods');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this payment method.', $author);
  }

}
