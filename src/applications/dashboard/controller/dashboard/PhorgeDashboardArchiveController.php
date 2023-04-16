<?php

final class PhorgeDashboardArchiveController
  extends PhorgeDashboardController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $dashboard = id(new PhorgeDashboardQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$dashboard) {
      return new Aphront404Response();
    }

    $view_uri = $dashboard->getURI();

    if ($request->isFormPost()) {
      if ($dashboard->isArchived()) {
        $new_status = PhorgeDashboard::STATUS_ACTIVE;
      } else {
        $new_status = PhorgeDashboard::STATUS_ARCHIVED;
      }

      $xactions = array();

      $xactions[] = id(new PhorgeDashboardTransaction())
        ->setTransactionType(
          PhorgeDashboardStatusTransaction::TRANSACTIONTYPE)
        ->setNewValue($new_status);

      id(new PhorgeDashboardTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($dashboard, $xactions);

      return id(new AphrontRedirectResponse())->setURI($view_uri);
    }

    if ($dashboard->isArchived()) {
      $title = pht('Activate Dashboard');
      $body = pht('This dashboard will become active again.');
      $button = pht('Activate Dashboard');
    } else {
      $title = pht('Archive Dashboard');
      $body = pht('This dashboard will be marked as archived.');
      $button = pht('Archive Dashboard');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendChild($body)
      ->addCancelButton($view_uri)
      ->addSubmitButton($button);
  }

}
