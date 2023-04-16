<?php

final class PhorgeBadgesEditRecipientsController
  extends PhorgeBadgesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');
    $xactions = array();

    $badge = id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_EDIT,
          PhorgePolicyCapability::CAN_VIEW,
        ))
      ->executeOne();
    if (!$badge) {
      return new Aphront404Response();
    }

    $view_uri = $this->getApplicationURI('recipients/'.$badge->getID().'/');

    if ($request->isFormPost()) {
      $award_phids = array();

      $add_recipients = $request->getArr('phids');
      if ($add_recipients) {
        foreach ($add_recipients as $phid) {
          $award_phids[] = $phid;
        }
      }

      $xactions[] = id(new PhorgeBadgesTransaction())
        ->setTransactionType(
          PhorgeBadgesBadgeAwardTransaction::TRANSACTIONTYPE)
        ->setNewValue($award_phids);

      $editor = id(new PhorgeBadgesEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($badge, $xactions);

      return id(new AphrontRedirectResponse())
        ->setURI($view_uri);
    }

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $badge,
      PhorgePolicyCapability::CAN_EDIT);

    $form_box = null;
    $title = pht('Add Recipient');
    if ($can_edit) {
      $header_name = pht('Edit Recipients');

      $form = new AphrontFormView();
      $form
        ->setUser($viewer)
        ->setFullWidth(true)
        ->appendControl(
          id(new AphrontFormTokenizerControl())
            ->setName('phids')
            ->setLabel(pht('Recipients'))
            ->setDatasource(new PhorgePeopleDatasource()));
    }

    $dialog = id(new AphrontDialogView())
      ->setUser($viewer)
      ->setTitle(pht('Add Recipients'))
      ->appendForm($form)
      ->addCancelButton($view_uri)
      ->addSubmitButton(pht('Add Recipients'));

    return $dialog;
  }

}
