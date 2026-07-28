<?php

final class PassphrasePasswordKey extends PassphraseAbstractKey {

  public static function loadFromPHID($phid, PhabricatorUser $viewer) {
    $key = new self();
    return $key->loadAndValidateFromPHID(
      $phid,
      $viewer,
      PassphrasePasswordCredentialType::PROVIDES_TYPE);
  }

  public function getPasswordEnvelope() {
    return $this->requireCredential()->getSecret();
  }

}
