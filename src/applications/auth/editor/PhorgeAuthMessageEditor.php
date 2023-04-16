<?php

final class PhorgeAuthMessageEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Auth Messages');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this message.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

}
