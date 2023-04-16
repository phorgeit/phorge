<?php

final class PhorgeProjectColumnHideController
  extends PhorgeProjectBoardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');
    $project_id = $request->getURIData('projectID');

    $project = id(new PhorgeProjectQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->withIDs(array($project_id))
      ->executeOne();

    if (!$project) {
      return new Aphront404Response();
    }
    $this->setProject($project);

    $column = id(new PhorgeProjectColumnQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$column) {
      return new Aphront404Response();
    }

    $column_phid = $column->getPHID();

    $view_uri = $project->getWorkboardURI();
    $view_uri = new PhutilURI($view_uri);
    foreach ($request->getPassthroughRequestData() as $key => $value) {
      $view_uri->replaceQueryParam($key, $value);
    }

    if ($column->isDefaultColumn()) {
      return $this->newDialog()
        ->setTitle(pht('Can Not Hide Default Column'))
        ->appendParagraph(
          pht('You can not hide the default/backlog column on a board.'))
        ->addCancelButton($view_uri, pht('Okay'));
    }

    $proxy = $column->getProxy();

    if ($request->isFormPost()) {
      if ($proxy) {
        if ($proxy->isArchived()) {
          $new_status = PhorgeProjectStatus::STATUS_ACTIVE;
        } else {
          $new_status = PhorgeProjectStatus::STATUS_ARCHIVED;
        }

        $xactions = array();

        $xactions[] = id(new PhorgeProjectTransaction())
          ->setTransactionType(
              PhorgeProjectStatusTransaction::TRANSACTIONTYPE)
          ->setNewValue($new_status);

        id(new PhorgeProjectTransactionEditor())
          ->setActor($viewer)
          ->setContentSourceFromRequest($request)
          ->setContinueOnNoEffect(true)
          ->setContinueOnMissingFields(true)
          ->applyTransactions($proxy, $xactions);
      } else {
        if ($column->isHidden()) {
          $new_status = PhorgeProjectColumn::STATUS_ACTIVE;
        } else {
          $new_status = PhorgeProjectColumn::STATUS_HIDDEN;
        }

        $type_status =
          PhorgeProjectColumnStatusTransaction::TRANSACTIONTYPE;

        $xactions = array(
          id(new PhorgeProjectColumnTransaction())
            ->setTransactionType($type_status)
            ->setNewValue($new_status),
        );

        $editor = id(new PhorgeProjectColumnTransactionEditor())
          ->setActor($viewer)
          ->setContinueOnNoEffect(true)
          ->setContinueOnMissingFields(true)
          ->setContentSourceFromRequest($request)
          ->applyTransactions($column, $xactions);
      }

      return id(new AphrontRedirectResponse())->setURI($view_uri);
    }

    if ($proxy) {
      if ($column->isHidden()) {
        $title = pht('Activate and Show Column');
        $body = pht(
          'This column is hidden because it represents an archived '.
          'subproject. Do you want to activate the subproject so the '.
          'column is visible again?');
        $button = pht('Activate Subproject');
      } else {
        $title = pht('Archive and Hide Column');
        $body = pht(
          'This column is visible because it represents an active '.
          'subproject. Do you want to hide the column by archiving the '.
          'subproject?');
        $button = pht('Archive Subproject');
      }
    } else {
      if ($column->isHidden()) {
        $title = pht('Show Column');
        $body = pht('Are you sure you want to show this column?');
        $button = pht('Show Column');
      } else {
        $title = pht('Hide Column');
        $body = pht(
          'Are you sure you want to hide this column? It will no longer '.
          'appear on the workboard.');
        $button = pht('Hide Column');
      }
    }

    $dialog = $this->newDialog()
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->setTitle($title)
      ->appendChild($body)
      ->setDisableWorkflowOnCancel(true)
      ->addCancelButton($view_uri)
      ->addSubmitButton($button);

    foreach ($request->getPassthroughRequestData() as $key => $value) {
      $dialog->addHiddenInput($key, $value);
    }

    return $dialog;
  }
}
