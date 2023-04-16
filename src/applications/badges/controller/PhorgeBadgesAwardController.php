<?php

final class PhorgeBadgesAwardController
  extends PhorgeBadgesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $user = id(new PhorgePeopleQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$user) {
      return new Aphront404Response();
    }

    $view_uri = '/people/badges/'.$user->getID().'/';

    if ($request->isFormPost()) {
      $badge_phids = $request->getArr('badgePHIDs');
      $badges = id(new PhorgeBadgesQuery())
        ->setViewer($viewer)
        ->withPHIDs($badge_phids)
        ->requireCapabilities(
          array(
            PhorgePolicyCapability::CAN_EDIT,
            PhorgePolicyCapability::CAN_VIEW,
          ))
        ->execute();
      if (!$badges) {
        return new Aphront404Response();
      }
      $award_phids = array($user->getPHID());

      foreach ($badges as $badge) {
        $xactions = array();
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
      }

      return id(new AphrontRedirectResponse())
        ->setURI($view_uri);
    }

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendControl(
        id(new AphrontFormTokenizerControl())
          ->setLabel(pht('Badge'))
          ->setName('badgePHIDs')
          ->setDatasource(
            id(new PhorgeBadgesDatasource())
              ->setParameters(
                array(
                  'recipientPHID' => $user->getPHID(),
                  ))));

    $dialog = $this->newDialog()
      ->setTitle(pht('Award Badge'))
      ->appendForm($form)
      ->addCancelButton($view_uri)
      ->addSubmitButton(pht('Award'));

    return $dialog;
  }

}
