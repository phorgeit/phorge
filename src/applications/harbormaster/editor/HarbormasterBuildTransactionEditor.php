<?php

final class HarbormasterBuildTransactionEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorHarbormasterApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Harbormaster Builds');
  }

}
