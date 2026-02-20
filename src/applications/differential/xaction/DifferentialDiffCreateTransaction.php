<?php

final class DifferentialDiffCreateTransaction
  extends DifferentialDiffTransactionType {

  const TRANSACTIONTYPE = DifferentialDiffTransaction::TYPE_DIFF_CREATE;

  public function shouldHide() {
    return true;
  }

  public function generateOldValue($object) {
    return null;
  }

  public function getActionName() {
    return pht('Created');
  }

  public function getTitle() {
    return pht('%s created this diff.', $this->renderAuthor());
  }

  public function getIcon() {
    return 'fa-refresh';
  }

  public function getColor() {
    return PhabricatorTransactions::COLOR_SKY;
  }

  public function extractFilePHIDs($object, $value) {

    /** @var array<DifferentialChangeset> $changesets */
    $changesets = $object->getChangesets();

    $file_phids = array();
    foreach ($changesets as $change) {
      $file_phids[] = $change->getNewFileObjectPHID();
      $file_phids[] = $change->getOldFileObjectPHID();
    }

    return array_filter($file_phids);
  }

  public function applyInternalEffects($object, $value) {
    $this->updateDiffFromDict($object, $value);
  }


  /**
   * We run Herald as part of transaction validation because Herald can
   * block diff creation for Differential diffs. Its important to do this
   * separately so no Herald logs are saved; these logs could expose
   * information the Herald rules are intended to block.
   */
  public function validateTransactions($object, array $xactions) {

    $errors = array();

    foreach ($xactions as $xaction) {
      $diff = clone $object;
      $diff = $this->updateDiffFromDict($diff, $xaction->getNewValue());

      $adapter = $this->buildHeraldAdapter($diff);
      $adapter->setContentSource($xaction->getContentSource());
      $adapter->setIsNewObject(true);

      $engine = new HeraldEngine();

      $rules = $engine->loadRulesForAdapter($adapter);
      $rules = mpull($rules, null, 'getID');

      $effects = $engine->applyRules($rules, $adapter);
      $action_block = DifferentialBlockHeraldAction::ACTIONCONST;

      $blocking_effect = null;
      foreach ($effects as $effect) {
        if ($effect->getAction() == $action_block) {
          $blocking_effect = $effect;
          break;
        }
      }

      if ($blocking_effect) {
        $rule = $blocking_effect->getRule();

        $message = $blocking_effect->getTarget();
        if (!is_string($message) || !strlen($message)) {
          $message = pht('(None.)');
        }

        $errors[] = $this->newError(
          pht('Rejected by Herald'),
          pht(
            "Creation of this diff was rejected by Herald rule %s.\n".
            "  Rule: %s\n".
            "Reason: %s",
            $rule->getMonogram(),
            $rule->getName(),
            $message));
      }
    }

    return $errors;
  }

  private function buildHeraldAdapter($object) {

    return id(new HeraldDifferentialDiffAdapter())
      ->setDiff($object);
  }

  private function updateDiffFromDict(DifferentialDiff $diff, $dict) {
    $diff
      ->setSourcePath(idx($dict, 'sourcePath'))
      ->setSourceMachine(idx($dict, 'sourceMachine'))
      ->setBranch(idx($dict, 'branch'))
      ->setCreationMethod(idx($dict, 'creationMethod'))
      ->setAuthorPHID(idx($dict, 'authorPHID', $this->getActor()))
      ->setBookmark(idx($dict, 'bookmark'))
      ->setRepositoryPHID(idx($dict, 'repositoryPHID'))
      ->setRepositoryUUID(idx($dict, 'repositoryUUID'))
      ->setSourceControlSystem(idx($dict, 'sourceControlSystem'))
      ->setSourceControlPath(idx($dict, 'sourceControlPath'))
      ->setSourceControlBaseRevision(idx($dict, 'sourceControlBaseRevision'))
      ->setLintStatus(idx($dict, 'lintStatus'))
      ->setUnitStatus(idx($dict, 'unitStatus'));

    return $diff;
  }
}
