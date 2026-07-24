<?php

final class PhabricatorProjectArchiveController
  extends PhabricatorProjectController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $project = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$project) {
      return new Aphront404Response();
    }

    $edit_uri = $this->getApplicationURI('manage/'.$project->getID().'/');

    if ($request->isFormPost()) {
      if ($project->isArchived()) {
        $new_status = PhabricatorProjectStatus::STATUS_ACTIVE;
      } else {
        $new_status = PhabricatorProjectStatus::STATUS_ARCHIVED;
      }

      $xactions = array();

      $xactions[] = id(new PhabricatorProjectTransaction())
        ->setTransactionType(
            PhabricatorProjectStatusTransaction::TRANSACTIONTYPE)
        ->setNewValue($new_status);

      id(new PhabricatorProjectTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($project, $xactions);

      return id(new AphrontRedirectResponse())->setURI($edit_uri);
    }

    $tasks_text = null;

    if ($project->isArchived()) {
      $title = pht('Really activate project?');
      $body = pht('This project will become active again.');
      $button = pht('Activate Project');
    } else {

      // Warn in the Archiving dialog about potentially lingering open tasks
      // which have no other active project tags associated.
      $open_tasks = id(new ManiphestTaskQuery())
        ->setViewer($viewer)
        ->withStatuses(ManiphestTaskStatus::getOpenStatusConstants())
        ->needProjectPHIDs(true)
        ->withEdgeLogicPHIDs(
          PhabricatorProjectObjectHasProjectEdgeType::EDGECONST,
          PhabricatorQueryConstraint::OPERATOR_AND,
          array($project->getPHID()))
        ->execute();

      $project_phids = array_mergev(mpull($open_tasks, 'getProjectPHIDs'));
      $project_phids = array_diff($project_phids, array($project->getPHID()));
      $active_projects = id(new PhabricatorProjectQuery())
        ->setViewer($this->getViewer())
        ->withStatus(PhabricatorProjectQuery::STATUS_ACTIVE)
        ->withPHIDs($project_phids)
        ->execute();
      $active_phids = mpull($active_projects, 'getPHID');

      foreach ($open_tasks as $key => $task) {
        $task_phids = $task->getProjectPHIDs();
        foreach ($task_phids as $task_phid) {
          if (in_array($task_phid, $active_phids)) {
            unset($open_tasks[$key]);
            break;
          }
        }
      }
      $tasks_ids = mpull($open_tasks, 'getID');

      $lingering = null;
      if ($tasks_ids) {
        $tasks_text = pht(
          'WARNING: This project has [[%s|%s open task(s) with no active '.
          'project tags]]. Before archiving, consider closing the task(s) or '.
          'adding active project tags to the task(s).',
          '/maniphest/?ids='.implode(',', $tasks_ids).'#R',
            phutil_count($tasks_ids));
      }

      $title = pht('Really archive project?');
      $body = pht('This project will be moved to the archive.');
      $button = pht('Archive Project');
    }

    $dialog = id(new AphrontDialogView())
      ->setUser($viewer)
      ->setTitle($title)
      ->appendChild($body)
      ->addCancelButton($edit_uri)
      ->addSubmitButton($button);

    if ($tasks_text) {
      $dialog->appendRemarkup($tasks_text);
    }

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }

}
