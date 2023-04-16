<?php

final class PhorgeCalendarExternalInviteeQuery
  extends PhorgeCursorPagedPolicyAwareQuery {

  private $ids;
  private $phids;
  private $names;

  public function withIDs(array $ids) {
    $this->ids = $ids;
    return $this;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  public function withNames(array $names) {
    $this->names = $names;
    return $this;
  }

  public function newResultObject() {
    return new PhorgeCalendarExternalInvitee();
  }

  protected function buildWhereClauseParts(AphrontDatabaseConnection $conn) {
    $where = parent::buildWhereClauseParts($conn);

    if ($this->ids !== null) {
      $where[] = qsprintf(
        $conn,
        'id IN (%Ld)',
        $this->ids);
    }

    if ($this->phids !== null) {
      $where[] = qsprintf(
        $conn,
        'phid IN (%Ls)',
        $this->phids);
    }

    if ($this->names !== null) {
      $name_indexes = array();
      foreach ($this->names as $name) {
        $name_indexes[] = PhorgeHash::digestForIndex($name);
      }
      $where[] = qsprintf(
        $conn,
        'nameIndex IN (%Ls)',
        $name_indexes);
    }

    return $where;
  }

  public function getQueryApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

}
