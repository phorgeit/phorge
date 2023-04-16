<?php

abstract class PonderEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePonderApplication';
  }

   protected function getMailSubjectPrefix() {
    return '[Ponder]';
  }

}
