<?php

final class PhortunePaymentMethodEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Payment Methods');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this payment method.', $author);
  }

}
