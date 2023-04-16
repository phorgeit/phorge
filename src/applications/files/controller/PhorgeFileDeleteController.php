<?php

final class PhorgeFileDeleteController extends PhorgeFileController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $file = id(new PhorgeFileQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->withIsDeleted(false)
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$file) {
      return new Aphront404Response();
    }

    if (($viewer->getPHID() != $file->getAuthorPHID()) &&
        (!$viewer->getIsAdmin())) {
      return new Aphront403Response();
    }

    if ($request->isFormPost()) {
      $xactions = array();

      $xactions[] = id(new PhorgeFileTransaction())
        ->setTransactionType(PhorgeFileDeleteTransaction::TRANSACTIONTYPE)
        ->setNewValue(true);

      id(new PhorgeFileEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($file, $xactions);

      return id(new AphrontRedirectResponse())->setURI('/file/');
    }

    return $this->newDialog()
      ->setTitle(pht('Really delete file?'))
      ->appendChild(hsprintf(
      '<p>%s</p>',
      pht(
        'Permanently delete "%s"? This action can not be undone.',
        $file->getName())))
        ->addSubmitButton(pht('Delete'))
        ->addCancelButton($file->getInfoURI());
  }
}
