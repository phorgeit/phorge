<?php

final class PhorgeCalendarExportDisableController
  extends PhorgeCalendarController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $export = id(new PhorgeCalendarExportQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$export) {
      return new Aphront404Response();
    }

    $export_uri = $export->getURI();
    $is_disable = !$export->getIsDisabled();

    if ($request->isFormPost()) {
      $xactions = array();
      $xactions[] = id(new PhorgeCalendarExportTransaction())
        ->setTransactionType(
          PhorgeCalendarExportDisableTransaction::TRANSACTIONTYPE)
        ->setNewValue($is_disable ? 1 : 0);

      $editor = id(new PhorgeCalendarExportEditor())
        ->setActor($viewer)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->setContentSourceFromRequest($request);

      $editor->applyTransactions($export, $xactions);

      return id(new AphrontRedirectResponse())->setURI($export_uri);
    }

    if ($is_disable) {
      $title = pht('Disable Export');
      $body = pht(
        'Disable this export? The export URI will no longer function.');
      $button = pht('Disable Export');
    } else {
      $title = pht('Enable Export');
      $body = pht(
        'Enable this export? Anyone who knows the export URI will be able '.
        'to export the data.');
      $button = pht('Enable Export');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendParagraph($body)
      ->addCancelButton($export_uri)
      ->addSubmitButton($button);
  }

}
