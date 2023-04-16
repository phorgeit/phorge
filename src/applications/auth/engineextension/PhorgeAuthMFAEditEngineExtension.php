<?php

final class PhorgeAuthMFAEditEngineExtension
  extends PhorgeEditEngineExtension {

  const EXTENSIONKEY = 'auth.mfa';
  const FIELDKEY = 'mfa';

  public function getExtensionPriority() {
    return 12000;
  }

  public function isExtensionEnabled() {
    return true;
  }

  public function getExtensionName() {
    return pht('MFA');
  }

  public function supportsObject(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {
    return true;
  }

  public function buildCustomEditFields(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object) {

    $mfa_type = PhorgeTransactions::TYPE_MFA;

    $viewer = $engine->getViewer();

    $mfa_field = id(new PhorgeApplyEditField())
      ->setViewer($viewer)
      ->setKey(self::FIELDKEY)
      ->setLabel(pht('MFA'))
      ->setIsFormField(false)
      ->setCommentActionLabel(pht('Sign With MFA'))
      ->setCanApplyWithoutEditCapability(true)
      ->setCommentActionOrder(12000)
      ->setActionDescription(
        pht('You will be prompted to provide MFA when you submit.'))
      ->setDescription(pht('Sign this transaction group with MFA.'))
      ->setTransactionType($mfa_type);

    return array(
      $mfa_field,
    );
  }

}
