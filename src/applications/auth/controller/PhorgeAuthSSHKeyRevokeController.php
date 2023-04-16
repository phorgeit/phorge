<?php

final class PhorgeAuthSSHKeyRevokeController
  extends PhorgeAuthSSHKeyController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $key = id(new PhorgeAuthSSHKeyQuery())
      ->setViewer($viewer)
      ->withIDs(array($request->getURIData('id')))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$key) {
      return new Aphront404Response();
    }

    $cancel_uri = $key->getURI();

    $token = id(new PhorgeAuthSessionEngine())->requireHighSecuritySession(
      $viewer,
      $request,
      $cancel_uri);

    if ($request->isFormPost()) {
      $xactions = array();

      $xactions[] = id(new PhorgeAuthSSHKeyTransaction())
        ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE)
        ->setNewValue(true);

      id(new PhorgeAuthSSHKeyEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->applyTransactions($key, $xactions);

      return id(new AphrontRedirectResponse())->setURI($cancel_uri);
    }

    $name = phutil_tag('strong', array(), $key->getName());

    return $this->newDialog()
      ->setTitle(pht('Revoke SSH Public Key'))
      ->appendParagraph(
        pht(
          'The key "%s" will be permanently revoked, and you will no '.
          'longer be able to use the corresponding private key to '.
          'authenticate.',
          $name))
      ->addSubmitButton(pht('Revoke Public Key'))
      ->addCancelButton($cancel_uri);
  }

}
