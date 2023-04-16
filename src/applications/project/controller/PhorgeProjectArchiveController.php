<?php

final class PhorgeProjectArchiveController
  extends PhorgeProjectController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $project = id(new PhorgeProjectQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$project) {
      return new Aphront404Response();
    }

    $edit_uri = $this->getApplicationURI('manage/'.$project->getID().'/');

    if ($request->isFormPost()) {
      if ($project->isArchived()) {
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
        ->applyTransactions($project, $xactions);

      return id(new AphrontRedirectResponse())->setURI($edit_uri);
    }

    if ($project->isArchived()) {
      $title = pht('Really activate project?');
      $body = pht('This project will become active again.');
      $button = pht('Activate Project');
    } else {
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

    return id(new AphrontDialogResponse())->setDialog($dialog);
  }

}
