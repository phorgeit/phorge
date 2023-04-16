<?php

final class PhorgeProjectBoardDisableController
  extends PhorgeProjectBoardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getUser();
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

    if (!$project->getHasWorkboard()) {
      return new Aphront404Response();
    }

    $this->setProject($project);
    $id = $project->getID();

    $board_uri = $this->getApplicationURI("board/{$id}/");

    if ($request->isFormPost()) {
      $xactions = array();

      $xactions[] = id(new PhorgeProjectTransaction())
        ->setTransactionType(
            PhorgeProjectWorkboardTransaction::TRANSACTIONTYPE)
        ->setNewValue(0);

      id(new PhorgeProjectTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($project, $xactions);

      return id(new AphrontRedirectResponse())
        ->setURI($board_uri);
    }

    return $this->newDialog()
      ->setTitle(pht('Disable Workboard'))
      ->appendParagraph(
        pht(
          'Disabling a workboard hides the board. Objects on the board '.
          'will no longer be annotated with column names in other '.
          'applications. You can restore the workboard later.'))
      ->addCancelButton($board_uri)
      ->addSubmitButton(pht('Disable Workboard'));
  }

}
