<?php

final class PhortuneSubscriptionPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PSUB';

  public function getTypeName() {
    return pht('Phortune Subscription');
  }

  public function newObject() {
    return new PhortuneSubscription();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortuneSubscriptionQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $subscription = $objects[$phid];

      $handle
        ->setName($subscription->getSubscriptionName())
        ->setURI($subscription->getURI());
    }
  }

}
