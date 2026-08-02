<?php

final class PhabricatorSubscriptionsUIEventListener
  extends PhabricatorEventListener {

  public function register() {
    $this->listen(PhabricatorEventType::TYPE_UI_WILLRENDERPROPERTIES);
  }

  public function handleEvent(PhutilEvent $event) {
    $object = $event->getValue('object');

    switch ($event->getType()) {
      case PhabricatorEventType::TYPE_UI_WILLRENDERPROPERTIES:
        // Hacky solution so that property list view on Diffusion
        // commits shows build status, but not Projects, Subscriptions,
        // or Tokens.
        if ($object instanceof PhabricatorRepositoryCommit) {
          return;
        }
        $this->handlePropertyEvent($event);
        break;
    }
  }


  private function handlePropertyEvent($event) {
    $user = $event->getUser();
    $object = $event->getValue('object');

    if (!$object || !$object->getPHID()) {
      // No object, or the object has no PHID yet..
      return;
    }

    if (!($object instanceof PhabricatorSubscribableInterface)) {
      // This object isn't subscribable.
      return;
    }

    $subscribers = PhabricatorSubscribersQuery::loadSubscribersForPHID(
      $object->getPHID());
    if ($subscribers) {
      $handles = id(new PhabricatorHandleQuery())
        ->setViewer($user)
        ->withPHIDs($subscribers)
        ->execute();
    } else {
      $handles = array();
    }
    $sub_view = id(new SubscriptionListStringBuilder())
      ->setObjectPHID($object->getPHID())
      ->setHandles($handles)
      ->buildPropertyString();

    $view = $event->getValue('view');
    $view->addProperty(pht('Subscribers'), $sub_view);
  }

}
