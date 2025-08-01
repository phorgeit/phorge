<?php

/**
 * @extends PhabricatorCursorPagedPolicyAwareQuery<PhabricatorUserEmail>
 */
final class PhabricatorPeopleUserEmailQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $ids;
  private $phids;
  private $userPhids;
  private $isVerified;

  public function withIDs(array $ids) {
    $this->ids = $ids;
    return $this;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  /**
   * With the specified User PHIDs.
   * @param array<string|null> $phids User PHIDs
   */
  public function withUserPHIDs(array $phids) {
    $this->userPhids = $phids;
    return $this;
  }

  /**
   * With a verified email or not.
   * @param bool|null $verified
   */
  public function withIsVerified($verified) {
    $this->isVerified = $verified;
    return $this;
  }

  public function newResultObject() {
    return new PhabricatorUserEmail();
  }

  protected function getPrimaryTableAlias() {
    return 'email';
  }

  protected function buildWhereClauseParts(AphrontDatabaseConnection $conn) {
    $where = parent::buildWhereClauseParts($conn);

    if ($this->ids !== null) {
      $where[] = qsprintf(
        $conn,
        'email.id IN (%Ld)',
        $this->ids);
    }

    if ($this->phids !== null) {
      $where[] = qsprintf(
        $conn,
        'email.phid IN (%Ls)',
        $this->phids);
    }

    if ($this->userPhids !== null) {
      $where[] = qsprintf(
        $conn,
        'email.userPHID IN (%Ls)',
        $this->userPhids);
    }

    if ($this->isVerified !== null) {
      $where[] = qsprintf(
        $conn,
        'email.isVerified = %d',
        (int)$this->isVerified);
    }

    return $where;
  }

  protected function willLoadPage(array $page) {

    $user_phids = mpull($page, 'getUserPHID');

    $users = id(new PhabricatorPeopleQuery())
      ->setViewer($this->getViewer())
      ->setParentQuery($this)
      ->withPHIDs($user_phids)
      ->execute();
    $users = mpull($users, null, 'getPHID');

    foreach ($page as $key => $address) {
      $user = idx($users, $address->getUserPHID());

      if (!$user) {
        unset($page[$key]);
        $this->didRejectResult($address);
        continue;
      }

      $address->attachUser($user);
    }

    return $page;
  }

  public function getQueryApplicationClass() {
    return PhabricatorPeopleApplication::class;
  }

}
