<?php

final class PhorgeAuthProviderConfigEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Auth Providers');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_ENABLE;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_LOGIN;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_REGISTRATION;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_LINK;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_UNLINK;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_TRUST_EMAILS;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_AUTO_LOGIN;
    $types[] = PhorgeAuthProviderConfigTransaction::TYPE_PROPERTY;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeAuthProviderConfigTransaction::TYPE_ENABLE:
        if ($object->getIsEnabled() === null) {
          return null;
        } else {
          return (int)$object->getIsEnabled();
        }
      case PhorgeAuthProviderConfigTransaction::TYPE_LOGIN:
        return (int)$object->getShouldAllowLogin();
      case PhorgeAuthProviderConfigTransaction::TYPE_REGISTRATION:
        return (int)$object->getShouldAllowRegistration();
      case PhorgeAuthProviderConfigTransaction::TYPE_LINK:
        return (int)$object->getShouldAllowLink();
      case PhorgeAuthProviderConfigTransaction::TYPE_UNLINK:
        return (int)$object->getShouldAllowUnlink();
      case PhorgeAuthProviderConfigTransaction::TYPE_TRUST_EMAILS:
        return (int)$object->getShouldTrustEmails();
      case PhorgeAuthProviderConfigTransaction::TYPE_AUTO_LOGIN:
        return (int)$object->getShouldAutoLogin();
      case PhorgeAuthProviderConfigTransaction::TYPE_PROPERTY:
        $key = $xaction->getMetadataValue(
          PhorgeAuthProviderConfigTransaction::PROPERTY_KEY);
        return $object->getProperty($key);
    }
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeAuthProviderConfigTransaction::TYPE_ENABLE:
      case PhorgeAuthProviderConfigTransaction::TYPE_LOGIN:
      case PhorgeAuthProviderConfigTransaction::TYPE_REGISTRATION:
      case PhorgeAuthProviderConfigTransaction::TYPE_LINK:
      case PhorgeAuthProviderConfigTransaction::TYPE_UNLINK:
      case PhorgeAuthProviderConfigTransaction::TYPE_TRUST_EMAILS:
      case PhorgeAuthProviderConfigTransaction::TYPE_AUTO_LOGIN:
      case PhorgeAuthProviderConfigTransaction::TYPE_PROPERTY:
        return $xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {
    $v = $xaction->getNewValue();
    switch ($xaction->getTransactionType()) {
      case PhorgeAuthProviderConfigTransaction::TYPE_ENABLE:
        return $object->setIsEnabled($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_LOGIN:
        return $object->setShouldAllowLogin($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_REGISTRATION:
        return $object->setShouldAllowRegistration($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_LINK:
        return $object->setShouldAllowLink($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_UNLINK:
        return $object->setShouldAllowUnlink($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_TRUST_EMAILS:
        return $object->setShouldTrustEmails($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_AUTO_LOGIN:
        return $object->setShouldAutoLogin($v);
      case PhorgeAuthProviderConfigTransaction::TYPE_PROPERTY:
        $key = $xaction->getMetadataValue(
          PhorgeAuthProviderConfigTransaction::PROPERTY_KEY);
        return $object->setProperty($key, $v);
    }
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {
    return;
  }

  protected function mergeTransactions(
    PhorgeApplicationTransaction $u,
    PhorgeApplicationTransaction $v) {

    $type = $u->getTransactionType();
    switch ($type) {
      case PhorgeAuthProviderConfigTransaction::TYPE_ENABLE:
      case PhorgeAuthProviderConfigTransaction::TYPE_LOGIN:
      case PhorgeAuthProviderConfigTransaction::TYPE_REGISTRATION:
      case PhorgeAuthProviderConfigTransaction::TYPE_LINK:
      case PhorgeAuthProviderConfigTransaction::TYPE_UNLINK:
      case PhorgeAuthProviderConfigTransaction::TYPE_TRUST_EMAILS:
      case PhorgeAuthProviderConfigTransaction::TYPE_AUTO_LOGIN:
        // For these types, last transaction wins.
        return $v;
    }

    return parent::mergeTransactions($u, $v);
  }

  protected function validateAllTransactions(
    PhorgeLiskDAO $object,
    array $xactions) {

    $errors = parent::validateAllTransactions($object, $xactions);

    $locked_config_key = 'auth.lock-config';
    $is_locked = PhorgeEnv::getEnvConfig($locked_config_key);

    if ($is_locked) {
      $errors[] = new PhorgeApplicationTransactionValidationError(
        null,
        pht('Config Locked'),
        pht('Authentication provider configuration is locked, and can not be '.
            'changed without being unlocked.'),
        null);
    }

    return $errors;
  }

}
