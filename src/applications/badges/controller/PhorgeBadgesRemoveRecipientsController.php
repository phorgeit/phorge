<?php

final class PhorgeBadgesRemoveRecipientsController
  extends PhorgeBadgesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $badge = id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$badge) {
      return new Aphront404Response();
    }

    $remove_phid = $request->getStr('phid');
    $view_uri = $this->getApplicationURI('recipients/'.$badge->getID().'/');

    if ($request->isFormPost()) {
      $xactions = array();
      $xactions[] = id(new PhorgeBadgesTransaction())
        ->setTransactionType(
          PhorgeBadgesBadgeRevokeTransaction::TRANSACTIONTYPE)
        ->setNewValue(array($remove_phid));

      $editor = id(new PhorgeBadgesEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($badge, $xactions);

      return id(new AphrontRedirectResponse())
        ->setURI($view_uri);
    }

    $handle = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($remove_phid))
      ->executeOne();

    $dialog = id(new AphrontDialogView())
      ->setUser($viewer)
      ->setTitle(pht('Really Revoke Badge?'))
      ->appendParagraph(
        pht(
          'Really revoke the badge "%s" from %s?',
          phutil_tag('strong', array(), $badge->getName()),
          phutil_tag('strong', array(), $handle->getName())))
      ->addCancelButton($view_uri)
      ->addSubmitButton(pht('Revoke Badge'));

    return $dialog;
  }

}
