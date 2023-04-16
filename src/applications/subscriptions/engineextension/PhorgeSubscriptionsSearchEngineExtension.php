<?php

final class PhorgeSubscriptionsSearchEngineExtension
  extends PhorgeSearchEngineExtension {

  const EXTENSIONKEY = 'subscriptions';

  public function isExtensionEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeSubscriptionsApplication');
  }

  public function getExtensionName() {
    return pht('Support for Subscriptions');
  }

  public function getExtensionOrder() {
    return 2000;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeSubscribableInterface);
  }

  public function applyConstraintsToQuery(
    $object,
    $query,
    PhorgeSavedQuery $saved,
    array $map) {

    if (!empty($map['subscriberPHIDs'])) {
      $query->withEdgeLogicPHIDs(
        PhorgeObjectHasSubscriberEdgeType::EDGECONST,
        PhorgeQueryConstraint::OPERATOR_OR,
        $map['subscriberPHIDs']);
    }
  }

  public function getSearchFields($object) {
    $fields = array();

    $fields[] = id(new PhorgeSearchSubscribersField())
      ->setLabel(pht('Subscribers'))
      ->setKey('subscriberPHIDs')
      ->setConduitKey('subscribers')
      ->setAliases(array('subscriber', 'subscribers'))
      ->setDescription(
        pht('Search for objects with certain subscribers.'));

    return $fields;
  }

  public function getSearchAttachments($object) {
    return array(
      id(new PhorgeSubscriptionsSearchEngineAttachment())
        ->setAttachmentKey('subscribers'),
    );
  }

}
