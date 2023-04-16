<?php

final class PhorgeSubscriptionsFulltextEngineExtension
  extends PhorgeFulltextEngineExtension {

  const EXTENSIONKEY = 'subscriptions';

  public function getExtensionName() {
    return pht('Subscribers');
  }

  public function shouldEnrichFulltextObject($object) {
    return ($object instanceof PhorgeSubscribableInterface);
  }

  public function enrichFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {

    $subscriber_phids = PhorgeSubscribersQuery::loadSubscribersForPHID(
      $object->getPHID());

    if (!$subscriber_phids) {
      return;
    }

    $handles = id(new PhorgeHandleQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs($subscriber_phids)
      ->execute();

    foreach ($handles as $phid => $handle) {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_SUBSCRIBER,
        $phid,
        $handle->getType(),
        $document->getDocumentModified()); // Bogus timestamp.
    }
  }

}
