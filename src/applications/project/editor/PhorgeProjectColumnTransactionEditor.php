<?php

final class PhorgeProjectColumnTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Workboard Columns');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this column.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

}
