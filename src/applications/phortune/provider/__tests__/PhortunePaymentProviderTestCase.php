<?php

final class PhortunePaymentProviderTestCase extends PhorgeTestCase {

  public function testGetAllProviders() {
    PhortunePaymentProvider::getAllProviders();
    $this->assertTrue(true);
  }

}
