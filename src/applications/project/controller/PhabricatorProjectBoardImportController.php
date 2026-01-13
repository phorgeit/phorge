<?php

final class PhabricatorProjectBoardImportController
  extends PhabricatorProjectBoardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $project_id = $request->getURIData('projectID');

    $project = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->withIDs(array($project_id))
      ->executeOne();
    if (!$project) {
      return new Aphront404Response();
    }
    $this->setProject($project);

    $project_id = $project->getID();
    $board_uri = $this->getApplicationURI("board/{$project_id}/");

    // See PHI1025. We only want to prevent the import if the board already has
    // real columns. If it has proxy columns (for example, for milestones) you
    // can still import columns from another board.
    $columns = id(new PhabricatorProjectColumnQuery())
      ->setViewer($viewer)
      ->withProjectPHIDs(array($project->getPHID()))
      ->withIsProxyColumn(false)
      ->execute();
    if ($columns) {
      return $this->newDialog()
        ->setTitle(pht('Workboard Already Has Columns'))
        ->appendParagraph(
          pht(
            'You can not import columns into this workboard because it '.
            'already has columns. You can only import into an empty '.
            'workboard.'))
        ->addCancelButton($board_uri);
    }

    if ($request->isFormPost()) {
      $import_phid = $request->getArr('importProjectPHID');
      $import_phid = reset($import_phid);

      $import_columns = id(new PhabricatorProjectColumnQuery())
        ->setViewer($viewer)
        ->withProjectPHIDs(array($import_phid))
        ->withIsProxyColumn(false)
        ->execute();
      if (!$import_columns) {
        return $this->newDialog()
          ->setTitle(pht('Source Workboard Has No Columns'))
          ->appendParagraph(
            pht(
              'You can not import columns from that workboard because it has '.
              'no importable columns.'))
          ->addCancelButton($board_uri);
      }

      $table = id(new PhabricatorProjectColumn())
        ->openTransaction();
      foreach ($import_columns as $import_column) {
        if ($import_column->isHidden()) {
          continue;
        }

        $new_column = PhabricatorProjectColumn::initializeNewColumn($viewer)
          ->setSequence($import_column->getSequence())
          ->setProjectPHID($project->getPHID())
          ->setName($import_column->getName())
          ->setProperties($import_column->getProperties())
          ->save();
      }
      $xactions = array();
      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType(
            PhabricatorProjectWorkboardTransaction::TRANSACTIONTYPE)
        ->setNewValue(1);

      id(new PhabricatorProjectTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($project, $xactions);

      $table->saveTransaction();

      return id(new AphrontRedirectResponse())->setURI($board_uri);
    }

    // Default value. The Tokenizer wants an array of phids.
    $tokenizer_value = array();

    // Default to the previous milestone, if available.
    $value_candidate = $this->getPreviousMilestoneIfHasImportableColumns(
      $viewer, $project);

    if ($value_candidate) {
      $tokenizer_value[] = $value_candidate->getPHID();
    }

    $proj_selector = id(new AphrontFormTokenizerControl())
      ->setName('importProjectPHID')
      ->setUser($viewer)
      ->setValue($tokenizer_value)
      ->setDatasource(id(new PhabricatorProjectDatasource())
        ->setParameters(array('mustHaveColumns' => true))
      ->setLimit(1));

    return $this->newDialog()
      ->setTitle(pht('Import Columns'))
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->appendParagraph(pht('Choose a project or a milestone to import '.
        'columns from:'))
      ->appendChild($proj_selector)
      ->addCancelButton($board_uri)
      ->addSubmitButton(pht('Import'));
  }

  /**
   * Starting from a milestone, get the previous milestone,
   * but only if it has at least one column that could be imported.
   * @param  PhabricatorUser $viewer     Current user
   * @param  PhabricatorProject $project Current milestone with no
   *                                     workboard yet.
   *                                     Technically, this parameter
   *                                     could also be a project,
   *                                     and projects are silently
   *                                     rejected if passed here.
   * @return PhabricatorProject|null     The milestone preceding
   *                                     your specified milestone,
   *                                     but only if it has at least
   *                                     one importable column;
   *                                     null in any other case.
   */
  private function getPreviousMilestoneIfHasImportableColumns(
    PhabricatorUser $viewer,
    PhabricatorProject $milestone): ?PhabricatorProject {

    // We can suggest something, only if you are creating workboard
    // on a milestone.
    if (!$milestone->isMilestone()) {
      return null;
    }

    // Possible design choices:
    // 1. Suggest the most recent milestone (excluding this specific one).
    //    CONS: Suggesting the most recent milestone doesn't make much sense,
    //          when I want to create a workboard from a milestone from
    //          a year ago.
    //          Side note: to suggest the 'most recent', we need at least one
    //          more query to find that number. It needs a "SELECT MAX(n)".
    // 2. Suggest the precedent milestone.
    //    PRO: convenient when you already worked on a workboard, and you just
    //         create an additional milestone; convenient also when you create
    //         a workboard on a old milestone, so as to maintain the
    //         workboard structure of that time.
    //         Side note: finding the previous milestone is also extremely
    //         easy. We avoid the extra query "SELECT MAX(n)".
    //
    // So we go with: 2. Suggest the precedent milestone.
    $previous_milestone_num = (int)$milestone->getMilestoneNumber();
    $previous_milestone_num--;
    if ($previous_milestone_num < 1) {
      return null;
    }

    $previous_milestone = $this->getProjectMilestoneFromNumber(
      $viewer,
      $milestone->getParentProjectPHID(),
      $previous_milestone_num);

    if (!$previous_milestone) {
      return null;
    }

    // Micro-optimization to avoid querying columns.
    if (!$previous_milestone->getHasWorkboard()) {
      return null;
    }

    // Check if this milestone has at least one
    // existing column which could be imported, or we can cause the error
    // 'Source Workboard Has No Columns',
    // and it would be more confusing than useful.
    $example_column = $this->getOneImportableProjectColumn(
      $viewer,
      $previous_milestone->getPHID());

    if (!$example_column) {
      return null;
    }

    return $previous_milestone;
  }

  /**
   * Get the milestone of a project from its milestone number.
   * @param PhabricatorUser $viewer    Current user
   * @param string          $proj_phid Project PHID
   * @param int             $number    Milestone number
   */
  private function getProjectMilestoneFromNumber(
    PhabricatorUser $viewer,
    string $proj_phid, int $number): ?PhabricatorProject {

    $query_proj = new PhabricatorProjectQuery();
    return $query_proj
      ->setViewer($viewer)
      ->withParentProjectPHIDs(array($proj_phid))
      ->withIsMilestone(true)
      ->withMilestoneNumberBetween($number, $number)
      ->executeOne();
  }

  /**
   * Get whatever non-proxy column on the workboard,
   * if existing, except for the default "Backlog" column.
   * @param  PhabricatorUser               $viewer    Current user
   * @param  string                        $proj_phid Project PHID
   * @return PhabricatorProjectColumn|null            Column, or null
   *                                                  if there are none.
   */
  private function getOneImportableProjectColumn(
    PhabricatorUser $viewer,
    string $proj_phid): ?PhabricatorProjectColumn {

    // Query one (whatever) project column suitable for the import.
    // This code is inspired from PhabricatorProjectDatasource,
    // looking at its parameter 'mustHaveColumns'.
    $query_columns = new PhabricatorProjectColumnQuery();
    return $query_columns
      ->setViewer($viewer)
      ->withProjectPHIDs(array($proj_phid))
      ->withIsProxyColumn(false)
      ->setLimit(1)
      ->executeOne();
  }

}
