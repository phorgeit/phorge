<?php

final class PhabricatorSlowvoteCloseController
  extends PhabricatorSlowvoteController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $poll = id(new PhabricatorSlowvoteQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhabricatorPolicyCapability::CAN_VIEW,
          PhabricatorPolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$poll) {
      return new Aphront404Response();
    }

    $close_uri = $poll->getURI();

    if ($request->isFormPost()) {
      if ($poll->isClosed()) {
        $new_status = SlowvotePollStatus::STATUS_OPEN;
      } else {
        $new_status = SlowvotePollStatus::STATUS_CLOSED;
      }

      $xactions = array();

      $xactions[] = id(new PhabricatorSlowvoteTransaction())
        ->setTransactionType(
            PhabricatorSlowvoteStatusTransaction::TRANSACTIONTYPE)
        ->setNewValue($new_status);

      id(new PhabricatorSlowvoteEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($poll, $xactions);

      return id(new AphrontRedirectResponse())->setURI($close_uri);
    }

    if ($poll->isClosed()) {
      $title = pht('Reopen Poll');
      $content = pht('Are you sure you want to reopen the poll?');
      $submit = pht('Reopen');
    } else {
      $title = pht('Close Poll');
      $content = pht('Are you sure you want to close the poll?');
      $submit = pht('Close');
    }

    return $this->newDialog()
      ->setTitle($title)
      ->appendChild($content)
      ->addSubmitButton($submit)
      ->addCancelButton($close_uri);
  }

}
