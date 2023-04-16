<?php

final class PhorgeAuthContactNumberTestController
  extends PhorgeAuthContactNumberController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $number = id(new PhorgeAuthContactNumberQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$number) {
      return new Aphront404Response();
    }

    $id = $number->getID();
    $cancel_uri = $number->getURI();

    // NOTE: This is a global limit shared by all users.
    PhorgeSystemActionEngine::willTakeAction(
      array(id(new PhorgeAuthApplication())->getPHID()),
      new PhorgeAuthTestSMSAction(),
      1);

    if ($request->isFormPost()) {
      $uri = PhorgeEnv::getURI('/');
      $uri = new PhutilURI($uri);

      $mail = id(new PhorgeMetaMTAMail())
        ->setMessageType(PhorgeMailSMSMessage::MESSAGETYPE)
        ->addTos(array($viewer->getPHID()))
        ->setSensitiveContent(false)
        ->setBody(
          pht(
            'This is a terse test text message (from "%s").',
            $uri->getDomain()))
        ->save();

      return id(new AphrontRedirectResponse())->setURI($mail->getURI());
    }

    $number_display = phutil_tag(
      'strong',
      array(),
      $number->getDisplayName());

    return $this->newDialog()
      ->setTitle(pht('Set Test Message'))
      ->appendParagraph(
        pht(
          'Send a test message to %s?',
          $number_display))
      ->addSubmitButton(pht('Send SMS'))
      ->addCancelButton($cancel_uri);
  }

}
