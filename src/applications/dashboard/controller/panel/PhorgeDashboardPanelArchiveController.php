<?php

final class PhorgeDashboardPanelArchiveController
  extends PhorgeDashboardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $panel = id(new PhorgeDashboardPanelQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$panel) {
      return new Aphront404Response();
    }

    $next_uri = '/'.$panel->getMonogram();

    if ($request->isFormPost()) {
      $xactions = array();
      $xactions[] = id(new PhorgeDashboardPanelTransaction())
        ->setTransactionType(
          PhorgeDashboardPanelStatusTransaction::TRANSACTIONTYPE)
        ->setNewValue((int)!$panel->getIsArchived());

      id(new PhorgeDashboardPanelTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->applyTransactions($panel, $xactions);

      return id(new AphrontRedirectResponse())->setURI($next_uri);
    }

    if ($panel->getIsArchived()) {
      $title = pht('Activate Panel?');
      $body = pht(
        'This panel will be reactivated and appear in other interfaces as '.
        'an active panel.');
      $submit_text = pht('Activate Panel');
    } else {
      $title = pht('Archive Panel?');
      $body = pht(
        'This panel will be archived and no longer appear in lists of active '.
        'panels.');
      $submit_text = pht('Archive Panel');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendParagraph($body)
      ->addSubmitButton($submit_text)
      ->addCancelButton($next_uri);
  }

}
