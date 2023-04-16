<?php

final class PhorgeAuthSSHKeyTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testRevokeSSHKey() {
    $user = $this->generateNewTestUser();
    $raw_key = 'ssh-rsa hunter2';

    $ssh_key = PhorgeAuthSSHKey::initializeNewSSHKey($user, $user);

    // Add the key to the user's account.
    $xactions = array();
    $xactions[] = $ssh_key->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_NAME)
      ->setNewValue('key1');
    $xactions[] = $ssh_key->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_KEY)
      ->setNewValue($raw_key);
    $this->applyTransactions($user, $ssh_key, $xactions);

    $ssh_key->reload();
    $this->assertTrue((bool)$ssh_key->getIsActive());

    // Revoke it.
    $xactions = array();
    $xactions[] = $ssh_key->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE)
      ->setNewValue(true);
    $this->applyTransactions($user, $ssh_key, $xactions);

    $ssh_key->reload();
    $this->assertFalse((bool)$ssh_key->getIsActive());

    // Try to add the revoked key back. This should fail with a validation
    // error because the key was previously revoked by the user.
    $revoked_key = PhorgeAuthSSHKey::initializeNewSSHKey($user, $user);
    $xactions = array();
    $xactions[] = $ssh_key->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_NAME)
      ->setNewValue('key2');
    $xactions[] = $ssh_key->getApplicationTransactionTemplate()
      ->setTransactionType(PhorgeAuthSSHKeyTransaction::TYPE_KEY)
      ->setNewValue($raw_key);

    $caught = null;
    try {
      $this->applyTransactions($user, $ssh_key, $xactions);
    } catch (PhorgeApplicationTransactionValidationException $ex) {
      $errors = $ex->getErrors();
      $this->assertEqual(1, count($errors));
      $caught = head($errors)->getType();
    }

    $this->assertEqual(PhorgeAuthSSHKeyTransaction::TYPE_KEY, $caught);
  }

  private function applyTransactions(
    PhorgeUser $actor,
    PhorgeAuthSSHKey $key,
    array $xactions) {

    $content_source = $this->newContentSource();

    $editor = $key->getApplicationTransactionEditor()
      ->setActor($actor)
      ->setContinueOnNoEffect(true)
      ->setContinueOnMissingFields(true)
      ->setContentSource($content_source)
      ->applyTransactions($key, $xactions);
  }

}
