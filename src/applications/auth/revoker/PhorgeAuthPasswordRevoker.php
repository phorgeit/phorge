<?php

final class PhorgeAuthPasswordRevoker
  extends PhorgeAuthRevoker {

  const REVOKERKEY = 'password';

  public function getRevokerName() {
    return pht('Passwords');
  }

  public function getRevokerDescription() {
    return pht(
      "Revokes all stored passwords.\n\n".
      "Account passwords and VCS passwords (used to access repositories ".
      "over HTTP) will both be revoked. Passwords for any third party ".
      "applications which use shared password infrastructure will also ".
      "be revoked.\n\n".
      "Users will need to reset account passwords, possibly by using the ".
      "\"Forgot Password?\" link on the login page. They will also need ".
      "to reset VCS passwords.\n\n".
      "Passwords are revoked, not just removed. Users will be unable to ".
      "select the passwords they used previously and must choose new, ".
      "unique passwords.\n\n".
      "Revoking passwords will not terminate outstanding login sessions. ".
      "Use the \"session\" revoker in conjunction with this revoker to force ".
      "users to login again.");
  }

  public function getRevokerNextSteps() {
    return pht(
      'NOTE: Revoking passwords does not terminate existing sessions which '.
      'were established using the old passwords. To terminate existing '.
      'sessions, run the "session" revoker now.');
  }

  public function revokeAllCredentials() {
    $query = new PhorgeAuthPasswordQuery();
    return $this->revokeWithQuery($query);
  }

  public function revokeCredentialsFrom($object) {
    $query = id(new PhorgeAuthPasswordQuery())
      ->withObjectPHIDs(array($object->getPHID()));
    return $this->revokeWithQuery($query);
  }

  private function revokeWithQuery(PhorgeAuthPasswordQuery $query) {
    $viewer = $this->getViewer();

    $passwords = $query
      ->setViewer($viewer)
      ->withIsRevoked(false)
      ->execute();

    $content_source = PhorgeContentSource::newForSource(
      PhorgeDaemonContentSource::SOURCECONST);

    $revoke_type = PhorgeAuthPasswordRevokeTransaction::TRANSACTIONTYPE;

    $auth_phid = id(new PhorgeAuthApplication())->getPHID();
    foreach ($passwords as $password) {
      $xactions = array();

      $xactions[] = $password->getApplicationTransactionTemplate()
        ->setTransactionType($revoke_type)
        ->setNewValue(true);

      $editor = $password->getApplicationTransactionEditor()
        ->setActor($viewer)
        ->setActingAsPHID($auth_phid)
        ->setContinueOnNoEffect(true)
        ->setContinueOnMissingFields(true)
        ->setContentSource($content_source)
        ->applyTransactions($password, $xactions);
    }

    return count($passwords);
  }

}
