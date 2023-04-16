<?php

final class PassphraseCredentialTypeTestCase extends PhorgeTestCase {

  public function testGetAllTypes() {
    PassphraseCredentialType::getAllTypes();
    $this->assertTrue(true);
  }

}
