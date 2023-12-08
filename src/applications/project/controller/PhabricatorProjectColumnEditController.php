<?php

final class PhabricatorProjectColumnEditController
  extends PhabricatorProjectBoardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');
    $project_id = $request->getURIData('projectID');

    $project = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->withIDs(array($project_id))
      ->needImages(true)
      ->executeOne();

    if (!$project) {
      return new Aphront404Response();
    }
    $this->setProject($project);

    $is_new = ($id ? false : true);

    if (!$is_new) {
      $column = id(new PhabricatorProjectColumnQuery())
        ->setViewer($viewer)
        ->withIDs(array($id))
        ->requireCapabilities(
          array(
            PhabricatorPolicyCapability::CAN_VIEW,
            PhabricatorPolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$column) {
        return new Aphront404Response();
      }
    } else {
      $column = PhabricatorProjectColumn::initializeNewColumn($viewer);
    }

    $e_name = null;
    $e_limit = null;
    $e_milestone_name = null;

    $v_limit = $column->getPointLimit();
    $v_name = $column->getName();

    $proxy = $column->getProxy();

    // Is this a normal Column? Example: when true, this is not a Milestone.
    $is_column = !$proxy;

    // Is this a Milestone? Example: when true, this is not a normal Column.
    $is_milestone = $proxy && $proxy->isMilestone();

    // Milestone name, eventually coming from the proxed object.
    $v_milestone_name = null;
    if ($is_milestone) {
      $v_milestone_name = $proxy->getName();
    }

    $validation_exception = null;
    $view_uri = $project->getWorkboardURI();

    if ($request->isFormPost()) {
      $v_name = $request->getStr('name');
      $v_limit = $request->getStr('limit');
      $v_milestone_name = $request->getStr('milestone.name');

      if ($is_new) {
        $column->setProjectPHID($project->getPHID());
        $column->attachProject($project);

        $columns = id(new PhabricatorProjectColumnQuery())
          ->setViewer($viewer)
          ->withProjectPHIDs(array($project->getPHID()))
          ->execute();

        $new_sequence = 1;
        if ($columns) {
          $values = mpull($columns, 'getSequence');
          $new_sequence = max($values) + 1;
        }
        $column->setSequence($new_sequence);
      }

      $xactions = array();
      $xactions_milestone = array();

      $type_name = PhabricatorProjectColumnNameTransaction::TRANSACTIONTYPE;
      $type_limit = PhabricatorProjectColumnLimitTransaction::TRANSACTIONTYPE;
      $type_project_name = PhabricatorProjectNameTransaction::TRANSACTIONTYPE;

      if ($is_column) {
        // Transaction for Column name.
        $xactions[] = id(new PhabricatorProjectColumnTransaction())
          ->setTransactionType($type_name)
          ->setNewValue($v_name);
      } else if ($is_milestone) {
        // Transaction for Milestone name (that internally is a Project Name).
        $xactions_milestone[] = id(new PhabricatorProjectTransaction())
          ->setTransactionType($type_project_name)
          ->setNewValue($v_milestone_name);
      }

      $xactions[] = id(new PhabricatorProjectColumnTransaction())
        ->setTransactionType($type_limit)
        ->setNewValue($v_limit);

      try {
        $editor = id(new PhabricatorProjectColumnTransactionEditor())
          ->setActor($viewer)
          ->setContinueOnNoEffect(true)
          ->setContentSourceFromRequest($request)
          ->applyTransactions($column, $xactions);
      } catch (PhabricatorApplicationTransactionValidationException $ex) {
        // Error messages related to the Column (like invalid Name, etc.)
        $e_name = $ex->getShortMessage($type_name);
        $e_limit = $ex->getShortMessage($type_limit);
        $validation_exception = $ex;
      }

      // Save Milestone-related stuff but only if there were no prior problems
      // and only if we have changes.
      if (!$validation_exception && $xactions_milestone) {
        try {
          $editor_milestone = id(new PhabricatorProjectTransactionEditor())
            ->setActor($viewer)
            ->setContinueOnNoEffect(true)
            ->setContentSourceFromRequest($request)
            ->applyTransactions($proxy, $xactions_milestone);
        } catch (PhabricatorApplicationTransactionValidationException $ex) {
          // Error messages related to the Milestone (like invalid Name, etc.)
          $e_milestone_name = $ex->getShortMessage($type_project_name);
          $validation_exception = $ex;
        }
      }

      // Refresh the page only if there are no errors to show.
      if (!$validation_exception) {
        return id(new AphrontRedirectResponse())->setURI($view_uri);
      }
    }

    $form = id(new AphrontFormView())
      ->setUser($request->getUser());

    // Show the most appropriate input field for the name.
    if ($is_column) {
      $form->appendChild(
        id(new AphrontFormTextControl())
          ->setValue($v_name)
          ->setLabel(pht('Name'))
          ->setName('name')
          ->setError($e_name));
    } else if ($is_milestone) {
      $form->appendChild(
        id(new AphrontFormTextControl())
          ->setValue($v_milestone_name)
          ->setLabel(pht('Milestone Name'))
          ->setName('milestone.name')
          ->setError($e_milestone_name));
    }

    $form->appendChild(
      id(new AphrontFormTextControl())
        ->setValue($v_limit)
        ->setLabel(pht('Point Limit'))
        ->setName('limit')
        ->setError($e_limit)
        ->setCaption(
          pht('Maximum number of points of tasks allowed in the column.')));

    if ($is_new) {
      $title = pht('Create Column');
      $submit = pht('Create Column');
    } else {
      $title = pht('Edit %s', $column->getDisplayName());
      $submit = pht('Save Column');
    }

    return $this->newDialog()
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->setTitle($title)
      ->appendForm($form)
      ->setValidationException($validation_exception)
      ->addCancelButton($view_uri)
      ->addSubmitButton($submit);

  }
}
