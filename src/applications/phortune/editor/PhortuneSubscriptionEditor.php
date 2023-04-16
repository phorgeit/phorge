<?php

final class PhortuneSubscriptionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Subscriptions');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this subscription.', $author);
  }

}
