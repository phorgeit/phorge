<?php

final class DifferentialDiffEditor
  extends PhabricatorApplicationTransactionEditor {

  private $lookupRepository = true;

  public function setLookupRepository($bool) {
    $this->lookupRepository = $bool;
    return $this;
  }

  public function getEditorApplicationClass() {
    return PhabricatorDifferentialApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Differential Diffs');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = DifferentialDiffTransaction::TYPE_DIFF_CREATE;

    return $types;
  }

  protected function didApplyInternalEffects(
    PhabricatorLiskDAO $object,
    array $xactions) {

    // This method wasn't modularized.

    foreach ($xactions as $xaction) {
      switch ($xaction->getTransactionType()) {
        case DifferentialDiffTransaction::TYPE_DIFF_CREATE:
          $xaction->setNewValue(true);
          break;
      }
    }

    return $xactions;
  }


  protected function applyFinalEffects(
    PhabricatorLiskDAO $object,
    array $xactions) {

    // If we didn't get an explicit `repositoryPHID` (which means the client
    // is old, or couldn't figure out which repository the working copy
    // belongs to), apply heuristics to try to figure it out.

    if ($this->lookupRepository && !$object->getRepositoryPHID()) {
      $repository = id(new DifferentialRepositoryLookup())
        ->setDiff($object)
        ->setViewer($this->getActor())
        ->lookupRepository();
      if ($repository) {
        $object->setRepositoryPHID($repository->getPHID());
        $object->setRepositoryUUID($repository->getUUID());
        $object->save();
      }
    }

    return $xactions;
  }

/* -(  Herald Integration  )------------------------------------------------- */

  /**
   * See @{method:validateTransaction}. The only Herald action is to block
   * the creation of Diffs. We thus have to be careful not to save any
   * data and do this validation very early.
   */
  protected function shouldApplyHeraldRules(
    PhabricatorLiskDAO $object,
    array $xactions) {

    return false;
  }

}
