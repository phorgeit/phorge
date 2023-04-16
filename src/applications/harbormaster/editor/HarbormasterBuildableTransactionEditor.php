<?php

final class HarbormasterBuildableTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Harbormaster Buildables');
  }

}
