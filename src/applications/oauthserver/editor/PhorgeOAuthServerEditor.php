<?php

final class PhorgeOAuthServerEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeOAuthServerApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('OAuth Applications');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeOAuthServerTransaction::TYPE_NAME;
    $types[] = PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI;
    $types[] = PhorgeOAuthServerTransaction::TYPE_DISABLED;

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeOAuthServerTransaction::TYPE_NAME:
        return $object->getName();
      case PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI:
        return $object->getRedirectURI();
      case PhorgeOAuthServerTransaction::TYPE_DISABLED:
        return $object->getIsDisabled();
    }
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeOAuthServerTransaction::TYPE_NAME:
      case PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI:
        return $xaction->getNewValue();
      case PhorgeOAuthServerTransaction::TYPE_DISABLED:
        return (int)$xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeOAuthServerTransaction::TYPE_NAME:
        $object->setName($xaction->getNewValue());
        return;
      case PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI:
        $object->setRedirectURI($xaction->getNewValue());
        return;
      case PhorgeOAuthServerTransaction::TYPE_DISABLED:
        $object->setIsDisabled($xaction->getNewValue());
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeOAuthServerTransaction::TYPE_NAME:
      case PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI:
      case PhorgeOAuthServerTransaction::TYPE_DISABLED:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
      case PhorgeOAuthServerTransaction::TYPE_NAME:
        $missing = $this->validateIsEmptyTextField(
          $object->getName(),
          $xactions);

        if ($missing) {
          $error = new PhorgeApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('OAuth applications must have a name.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        }
        break;
      case PhorgeOAuthServerTransaction::TYPE_REDIRECT_URI:
        $missing = $this->validateIsEmptyTextField(
          $object->getRedirectURI(),
          $xactions);
        if ($missing) {
          $error = new PhorgeApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('OAuth applications must have a valid redirect URI.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        } else {
          foreach ($xactions as $xaction) {
            $redirect_uri = $xaction->getNewValue();

            try {
              $server = new PhorgeOAuthServer();
              $server->assertValidRedirectURI($redirect_uri);
            } catch (Exception $ex) {
              $errors[] = new PhorgeApplicationTransactionValidationError(
                $type,
                pht('Invalid'),
                $ex->getMessage(),
                $xaction);
            }
          }
        }
        break;
    }

    return $errors;
  }

}
