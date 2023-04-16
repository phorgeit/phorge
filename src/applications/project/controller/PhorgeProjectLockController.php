<?php

final class PhorgeProjectLockController
  extends PhorgeProjectController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $this->requireApplicationCapability(
      ProjectCanLockProjectsCapability::CAPABILITY);

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

    $done_uri = "/project/members/{$id}/";

    if (!$project->supportsEditMembers()) {
      return $this->newDialog()
        ->setTitle(pht('Membership Immutable'))
        ->appendChild(
          pht('This project does not support editing membership.'))
        ->addCancelButton($done_uri);
    }

    $is_locked = $project->getIsMembershipLocked();

    if ($request->isFormPost()) {
      $xactions = array();

      if ($is_locked) {
        $new_value = 0;
      } else {
        $new_value = 1;
      }

      $xactions[] = id(new PhorgeProjectTransaction())
        ->setTransactionType(
            PhorgeProjectLockTransaction::TRANSACTIONTYPE)
        ->setNewValue($new_value);

      $editor = id(new PhorgeProjectTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($project, $xactions);

      return id(new AphrontRedirectResponse())->setURI($done_uri);
    }

    if ($project->getIsMembershipLocked()) {
      $title = pht('Unlock Project');
      $body = pht(
        'If you unlock this project, members will be free to leave.');
      $button = pht('Unlock Project');
    } else {
      $title = pht('Lock Project');
      $body = pht(
        'If you lock this project, members will be prevented from '.
        'leaving it.');
      $button = pht('Lock Project');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendParagraph($body)
      ->addSubmitbutton($button)
      ->addCancelButton($done_uri);
  }

}
