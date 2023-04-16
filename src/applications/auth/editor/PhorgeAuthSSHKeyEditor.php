<?php

final class PhorgeAuthSSHKeyEditor
  extends PhorgeApplicationTransactionEditor {

  private $isAdministrativeEdit;

  public function setIsAdministrativeEdit($is_administrative_edit) {
    $this->isAdministrativeEdit = $is_administrative_edit;
    return $this;
  }

  public function getIsAdministrativeEdit() {
    return $this->isAdministrativeEdit;
  }

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('SSH Keys');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeAuthSSHKeyTransaction::TYPE_NAME;
    $types[] = PhorgeAuthSSHKeyTransaction::TYPE_KEY;
    $types[] = PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeAuthSSHKeyTransaction::TYPE_NAME:
        return $object->getName();
      case PhorgeAuthSSHKeyTransaction::TYPE_KEY:
        return $object->getEntireKey();
      case PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE:
        return !$object->getIsActive();
    }

  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeAuthSSHKeyTransaction::TYPE_NAME:
      case PhorgeAuthSSHKeyTransaction::TYPE_KEY:
        return $xaction->getNewValue();
      case PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE:
        return (bool)$xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $value = $xaction->getNewValue();
    switch ($xaction->getTransactionType()) {
      case PhorgeAuthSSHKeyTransaction::TYPE_NAME:
        $object->setName($value);
        return;
      case PhorgeAuthSSHKeyTransaction::TYPE_KEY:
        $public_key = PhorgeAuthSSHPublicKey::newFromRawKey($value);

        $type = $public_key->getType();
        $body = $public_key->getBody();
        $comment = $public_key->getComment();

        $object->setKeyType($type);
        $object->setKeyBody($body);
        $object->setKeyComment($comment);
        return;
      case PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE:
        if ($value) {
          $new = null;
        } else {
          $new = 1;
        }

        $object->setIsActive($new);
        return;
    }
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {
    return;
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);
    $viewer = $this->requireActor();

    switch ($type) {
      case PhorgeAuthSSHKeyTransaction::TYPE_NAME:
        $missing = $this->validateIsEmptyTextField(
          $object->getName(),
          $xactions);

        if ($missing) {
          $error = new PhorgeApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('SSH key name is required.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        }
        break;

      case PhorgeAuthSSHKeyTransaction::TYPE_KEY;
        $missing = $this->validateIsEmptyTextField(
          $object->getName(),
          $xactions);

        if ($missing) {
          $error = new PhorgeApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('SSH key material is required.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        } else {
          foreach ($xactions as $xaction) {
            $new = $xaction->getNewValue();

            try {
              $public_key = PhorgeAuthSSHPublicKey::newFromRawKey($new);
            } catch (Exception $ex) {
              $errors[] = new PhorgeApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                $ex->getMessage(),
                $xaction);
              continue;
            }

            // The database does not have a unique key on just the <keyBody>
            // column because we allow multiple accounts to revoke the same
            // key, so we can't rely on database constraints to prevent users
            // from adding keys that are on the revocation list back to their
            // accounts. Explicitly check for a revoked copy of the key.

            $revoked_keys = id(new PhorgeAuthSSHKeyQuery())
              ->setViewer($viewer)
              ->withObjectPHIDs(array($object->getObjectPHID()))
              ->withIsActive(0)
              ->withKeys(array($public_key))
              ->execute();
            if ($revoked_keys) {
              $errors[] = new PhorgeApplicationTransactionValidationError(
                $type,
                pht('Revoked'),
                pht(
                  'This key has been revoked. Choose or generate a new, '.
                  'unique key.'),
                $xaction);
              continue;
            }
          }
        }
        break;

      case PhorgeAuthSSHKeyTransaction::TYPE_DEACTIVATE:
        foreach ($xactions as $xaction) {
          if (!$xaction->getNewValue()) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('Invalid'),
              pht('SSH keys can not be reactivated.'),
              $xaction);
          }
        }
        break;
    }

    return $errors;
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();
    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhorgeAuthSSHKeyTransaction::TYPE_KEY,
      pht('Duplicate'),
      pht(
        'This public key is already associated with another user or device. '.
        'Each key must unambiguously identify a single unique owner.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }


  protected function shouldSendMail(
    PhorgeLiskDAO $object,
    array $xactions) {
    return true;
  }

  protected function getMailSubjectPrefix() {
    return pht('[SSH Key]');
  }

  protected function getMailThreadID(PhorgeLiskDAO $object) {
    return 'ssh-key-'.$object->getPHID();
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    // After making any change to an SSH key, drop the authfile cache so it
    // is regenerated the next time anyone authenticates.
    PhorgeAuthSSHKeyQuery::deleteSSHKeyCache();

    return $xactions;
  }


  protected function getMailTo(PhorgeLiskDAO $object) {
    return $object->getObject()->getSSHKeyNotifyPHIDs();
  }

  protected function getMailCC(PhorgeLiskDAO $object) {
    return array();
  }

  protected function buildReplyHandler(PhorgeLiskDAO $object) {
    return id(new PhorgeAuthSSHKeyReplyHandler())
      ->setMailReceiver($object);
  }

  protected function buildMailTemplate(PhorgeLiskDAO $object) {
    $id = $object->getID();
    $name = $object->getName();

    $mail = id(new PhorgeMetaMTAMail())
      ->setSubject(pht('SSH Key %d: %s', $id, $name));

    // The primary value of this mail is alerting users to account compromises,
    // so force delivery. In particular, this mail should still be delivered
    // even if "self mail" is disabled.
    $mail->setForceDelivery(true);

    return $mail;
  }

  protected function buildMailBody(
    PhorgeLiskDAO $object,
    array $xactions) {

    $body = parent::buildMailBody($object, $xactions);

    if (!$this->getIsAdministrativeEdit()) {
      $body->addTextSection(
        pht('SECURITY WARNING'),
        pht(
          'If you do not recognize this change, it may indicate your account '.
          'has been compromised.'));
    }

    $detail_uri = $object->getURI();
    $detail_uri = PhorgeEnv::getProductionURI($detail_uri);

    $body->addLinkSection(pht('SSH KEY DETAIL'), $detail_uri);

    return $body;
  }


  protected function getCustomWorkerState() {
    return array(
      'isAdministrativeEdit' => $this->isAdministrativeEdit,
    );
  }

  protected function loadCustomWorkerState(array $state) {
    $this->isAdministrativeEdit = idx($state, 'isAdministrativeEdit');
    return $this;
  }


}
