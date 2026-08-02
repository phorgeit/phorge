<?php

final class PhrequentUIExtension extends PHUIActionListExtension {

  const EXTENSIONKEY = 'phrequent';

  public function shouldEnableForObject($object) {
    return
      $object instanceof PhrequentTrackableInterface &&
      $object->getPHID();
  }

  public function getExtensionApplicationClass() {
    return PhabricatorPhrequentApplication::class;
  }


  protected function buildAction() {
    $viewer = $this->getViewer();
    /** @var PhrequentTrackableInterface & PhabricatorPolicyInterface */
    $object = $this->getObject();

    $tracking = PhrequentUserTimeQuery::isUserTrackingObject(
      $viewer,
      $object->getPHID());
    if (!$tracking) {
      $track_action = id(new PhabricatorActionView())
        ->setName(pht('Start Tracking Time'))
        ->setIcon('fa-clock-o')
        ->setWorkflow(true)
        ->setHref('/phrequent/track/start/'.$object->getPHID().'/');
    } else {
      $track_action = id(new PhabricatorActionView())
        ->setName(pht('Stop Tracking Time'))
        ->setIcon('fa-clock-o red')
        ->setWorkflow(true)
        ->setHref('/phrequent/track/stop/'.$object->getPHID().'/');
    }

    if (!$viewer->isLoggedIn()) {
      $track_action->setDisabled(true);
    }

    $track_action->setOrder(4000);

    return $track_action;
  }

}
