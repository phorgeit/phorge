<?php

abstract class PonderEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorPonderApplication::class;
  }

   protected function getMailSubjectPrefix() {
    return '[Ponder]';
  }

}
