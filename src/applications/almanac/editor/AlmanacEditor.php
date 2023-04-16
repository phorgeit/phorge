<?php

abstract class AlmanacEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

}
