<?php

final class PhabricatorBadgesAwardController
  extends PhabricatorBadgesController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');
    $errors = array();
    $e_badge = true;

    $user = id(new PhabricatorPeopleQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
    if (!$user) {
      return new Aphront404Response();
    }

    $view_uri = '/people/badges/'.$user->getID().'/';

    if ($request->isFormPost()) {
      $badge_phids = $request->getArr('badgePHIDs');

      if (empty($badge_phids)) {
        $errors[] = pht('Badge name is required.');
        $e_badge = pht('Required');
      }
      if (!$errors) {
        $badges = id(new PhabricatorBadgesQuery())
          ->setViewer($viewer)
          ->withPHIDs($badge_phids)
          ->requireCapabilities(
            array(
              PhabricatorPolicyCapability::CAN_EDIT,
              PhabricatorPolicyCapability::CAN_VIEW,
            ))
          ->execute();
        $award_phids = array($user->getPHID());

        foreach ($badges as $badge) {
          $xactions = array();
          $xactions[] = id(new PhabricatorBadgesTransaction())
            ->setTransactionType(
              PhabricatorBadgesBadgeAwardTransaction::TRANSACTIONTYPE)
            ->setNewValue($award_phids);

          $editor = id(new PhabricatorBadgesEditor())
            ->setActor($viewer)
            ->setContentSourceFromRequest($request)
            ->setContinueOnNoEffect(true)
            ->setContinueOnMissingFields(true)
            ->applyTransactions($badge, $xactions);
        }

        return id(new AphrontRedirectResponse())
          ->setURI($view_uri);
      }
    }

    $form = id(new AphrontFormView())
      ->setUser($viewer)
      ->appendControl(
        id(new AphrontFormTokenizerControl())
          ->setLabel(pht('Badge'))
          ->setName('badgePHIDs')
          ->setError($e_badge)
          ->setDatasource(
            id(new PhabricatorBadgesDatasource())
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
