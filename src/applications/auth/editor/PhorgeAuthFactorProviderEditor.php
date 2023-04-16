<?php

final class PhorgeAuthFactorProviderEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('MFA Providers');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this MFA provider.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

}
