<?php

/**
 * @extends PhabricatorCursorPagedPolicyAwareQuery<PhorgeNamedPolicy>
 */
final class PhorgeNamedPolicyQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $phids;
  private $ids;

  private $applyToObject;

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  public function withIDs(array $ids) {
    $this->ids = $ids;
    return $this;
  }

  public function withCanApplyToObject(?PhabricatorPolicyInterface $object) {
    $this->applyToObject = $object;
    return $this;
  }

  public function newResultObject() {
    return new PhorgeNamedPolicy();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  protected function buildWhereClauseParts(AphrontDatabaseConnection $conn) {
    $where = parent::buildWhereClauseParts($conn);

    if ($this->ids !== null) {
      $where[] = qsprintf(
        $conn,
        'named.id IN (%Ld)',
        $this->ids);
    }

    if ($this->phids !== null) {
      $where[] = qsprintf(
        $conn,
        'named.phid IN (%Ls)',
        $this->phids);
    }

    return $where;
  }


  protected function getPrimaryTableAlias() {
    return 'named';
  }

  protected function willFilterPage(array $page) {
    if ($this->applyToObject !== null) {
      foreach ($page as $key => $policy) {
        if (!$policy->canApplyToObject($this->applyToObject)) {
          unset($page[$key]);
        }
      }
    }

    return $page;
  }

}
