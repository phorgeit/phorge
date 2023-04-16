<?php

final class PhorgeAuthFactorProviderMessageController
  extends PhorgeAuthFactorProviderController {

  public function handleRequest(AphrontRequest $request) {
    $this->requireApplicationCapability(
      AuthManageProvidersCapability::CAPABILITY);

    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $provider = id(new PhorgeAuthFactorProviderQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$provider) {
      return new Aphront404Response();
    }

    $cancel_uri = $provider->getURI();
    $enroll_key =
      PhorgeAuthFactorProviderEnrollMessageTransaction::TRANSACTIONTYPE;

    $message = $provider->getEnrollMessage();

    if ($request->isFormOrHisecPost()) {
      $message = $request->getStr('message');

      $xactions = array();

      $xactions[] = id(new PhorgeAuthFactorProviderTransaction())
        ->setTransactionType($enroll_key)
        ->setNewValue($message);

      $editor = id(new PhorgeAuthFactorProviderEditor())
        ->setActor($viewer)
        ->setContentSourceFromRequest($request)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->setCancelURI($cancel_uri);

      $editor->applyTransactions($provider, $xactions);

      return id(new AphrontRedirectResponse())->setURI($cancel_uri);
    }

    $default_message = $provider->getEnrollDescription($viewer);
    $default_message = new PHUIRemarkupView($viewer, $default_message);

    $form = id(new AphrontFormView())
      ->setViewer($viewer)
      ->appendRemarkupInstructions(
        pht(
          'When users add a factor for this provider, they are given this '.
          'enrollment guidance by default:'))
      ->appendControl(
        id(new AphrontFormMarkupControl())
          ->setLabel(pht('Default Message'))
          ->setValue($default_message))
      ->appendRemarkupInstructions(
        pht(
          'You may optionally customize the enrollment message users are '.
          'presented with by providing a replacement message below:'))
      ->appendControl(
        id(new PhorgeRemarkupControl())
          ->setLabel(pht('Custom Message'))
          ->setName('message')
          ->setValue($message));

    return $this->newDialog()
      ->setTitle(pht('Change Enroll Message'))
      ->setWidth(AphrontDialogView::WIDTH_FORM)
      ->appendForm($form)
      ->addCancelButton($cancel_uri)
      ->addSubmitButton(pht('Save'));
  }

}
