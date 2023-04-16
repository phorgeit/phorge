<?php

final class PhorgePasteArchiveController
  extends PhorgePasteController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $paste = id(new PhorgePasteQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$paste) {
      return new Aphront404Response();
    }

    $view_uri = $paste->getURI();

    if ($request->isFormPost()) {
      if ($paste->isArchived()) {
        $new_status = PhorgePaste::STATUS_ACTIVE;
      } else {
        $new_status = PhorgePaste::STATUS_ARCHIVED;
      }

      $xactions = array();

      $xactions[] = id(new PhorgePasteTransaction())
        ->setTransactionType(PhorgePasteStatusTransaction::TRANSACTIONTYPE)
        ->setNewValue($new_status);

      id(new PhorgePasteEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($paste, $xactions);

      return id(new AphrontRedirectResponse())->setURI($view_uri);
    }

    if ($paste->isArchived()) {
      $title = pht('Activate Paste');
      $body = pht('This paste will become consumable again.');
      $button = pht('Activate Paste');
    } else {
      $title = pht('Archive Paste');
      $body = pht('This paste will be marked as expired.');
      $button = pht('Archive Paste');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendChild($body)
      ->addCancelButton($view_uri)
      ->addSubmitButton($button);
  }

}
