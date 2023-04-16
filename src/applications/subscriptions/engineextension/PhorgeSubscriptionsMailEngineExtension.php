<?php

final class PhorgeSubscriptionsMailEngineExtension
  extends PhorgeMailEngineExtension {

  const EXTENSIONKEY = 'subscriptions';

  public function supportsObject($object) {
    return ($object instanceof PhorgeSubscribableInterface);
  }

  public function newMailStampTemplates($object) {
    return array(
      id(new PhorgePHIDMailStamp())
        ->setKey('subscriber')
        ->setLabel(pht('Subscriber')),
    );
  }

  public function newMailStamps($object, array $xactions) {
    $editor = $this->getEditor();
    $viewer = $this->getViewer();

    $subscriber_phids = PhorgeEdgeQuery::loadDestinationPHIDs(
      $object->getPHID(),
      PhorgeObjectHasSubscriberEdgeType::EDGECONST);

    $this->getMailStamp('subscriber')
      ->setValue($subscriber_phids);
  }

}
