<?php

final class HarbormasterBuildTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Harbormaster Builds');
  }

}
