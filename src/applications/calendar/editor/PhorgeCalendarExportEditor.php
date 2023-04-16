<?php

final class PhorgeCalendarExportEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Calendar Exports');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this export.', $author);
  }

}
