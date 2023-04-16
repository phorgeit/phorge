<?php

final class PhorgeOwnersArchiveController
  extends PhorgeOwnersController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $package = id(new PhorgeOwnersPackageQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$package) {
      return new Aphront404Response();
    }

    $view_uri = $this->getApplicationURI('package/'.$package->getID().'/');

    if ($request->isFormPost()) {
      if ($package->isArchived()) {
        $new_status = PhorgeOwnersPackage::STATUS_ACTIVE;
      } else {
        $new_status = PhorgeOwnersPackage::STATUS_ARCHIVED;
      }

      $xactions = array();

      $type = PhorgeOwnersPackageStatusTransaction::TRANSACTIONTYPE;
      $xactions[] = id(new PhorgeOwnersPackageTransaction())
        ->setTransactionType($type)
        ->setNewValue($new_status);

      id(new PhorgeOwnersPackageTransactionEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($package, $xactions);

      return id(new AphrontRedirectResponse())->setURI($view_uri);
    }

    if ($package->isArchived()) {
      $title = pht('Activate Package');
      $body = pht('This package will become active again.');
      $button = pht('Activate Package');
    } else {
      $title = pht('Archive Package');
      $body = pht('This package will be marked as archived.');
      $button = pht('Archive Package');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendChild($body)
      ->addCancelButton($view_uri)
      ->addSubmitButton($button);
  }

}
