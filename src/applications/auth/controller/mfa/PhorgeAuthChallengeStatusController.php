<?php

final class PhorgeAuthChallengeStatusController
  extends PhorgeAuthController {

  public function shouldAllowPartialSessions() {
    // We expect that users may request the status of an MFA challenge when
    // they hit the session upgrade gate on login.
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $id = $request->getURIData('id');
    $now = PhorgeTime::getNow();

    $result = new PhorgeAuthChallengeUpdate();

    $challenge = id(new PhorgeAuthChallengeQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->withUserPHIDs(array($viewer->getPHID()))
      ->withChallengeTTLBetween($now, null)
      ->executeOne();
    if ($challenge) {
      $config = id(new PhorgeAuthFactorConfigQuery())
        ->setViewer($viewer)
        ->withPHIDs(array($challenge->getFactorPHID()))
        ->executeOne();
      if ($config) {
        $provider = $config->getFactorProvider();
        $factor = $provider->getFactor();

        $result = $factor->newChallengeStatusView(
          $config,
          $provider,
          $viewer,
          $challenge);
      }
    }

    return id(new AphrontAjaxResponse())
      ->setContent($result->newContent());
  }

}
