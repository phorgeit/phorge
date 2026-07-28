<?php

final class PhabricatorBcryptPasswordHasher
  extends PhabricatorPasswordHasher {

  public function getHumanReadableName() {
    return pht('bcrypt');
  }

  public function getHashName() {
    return 'bcrypt';
  }

  public function getHashLength() {
    return 60;
  }

  public function canHashPasswords() {
    return function_exists('password_hash');
  }

  public function getInstallInstructions() {
    // This should always be available since PHP 5.5, but do something useful
    // anyway.
    return pht('To use BCrypt, make the password_hash() function available.');
  }

  public function getStrength() {
    return 2.0;
  }

  public function getHumanReadableStrength() {
    return pht('Good');
  }

  protected function getPasswordHash(PhutilOpaqueEnvelope $envelope) {
    $raw_input = $envelope->openEnvelope();

    $options = array(
      'cost' => $this->getBcryptCost(),
    );

    $raw_hash = password_hash($raw_input, PASSWORD_BCRYPT, $options);

    return new PhutilOpaqueEnvelope($raw_hash);
  }

  protected function verifyPassword(
    PhutilOpaqueEnvelope $password,
    PhutilOpaqueEnvelope $hash) {
    return password_verify($password->openEnvelope(), $hash->openEnvelope());
  }

  protected function canUpgradeInternalHash(PhutilOpaqueEnvelope $hash) {
    $info = password_get_info($hash->openEnvelope());

    // NOTE: If the costs don't match -- even if the new cost is lower than
    // the old cost -- count this as an upgrade. This allows costs to be
    // adjusted down and hashing to be migrated toward the new cost if costs
    // are ever configured too high for some reason.

    $cost = idx($info['options'], 'cost');
    if ($cost != $this->getBcryptCost()) {
      return true;
    }

    return false;
  }

  private function getBcryptCost() {
    // NOTE: The default cost is "12" as of 2026; it was "10" in 2014. Since
    // server hardware is often virtualized or old, we went for "11" in 2014.
    return 11;
  }

}
