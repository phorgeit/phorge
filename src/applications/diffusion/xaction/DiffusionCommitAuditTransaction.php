<?php

abstract class DiffusionCommitAuditTransaction
  extends DiffusionCommitActionTransaction {

  protected function getCommitActionGroupKey() {
    return DiffusionCommitEditEngine::ACTIONGROUP_AUDIT;
  }

  public function generateOldValue($object) {
    return false;
  }

  protected function isViewerAnyAuditor(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer) {
    return ($this->getViewerAuditStatus($commit, $viewer) !== null);
  }

  protected function isViewerAnyActiveAuditor(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer) {

    // This omits inactive states; currently just "Resigned".
    $active = array(
      PhorgeAuditRequestStatus::AUDIT_REQUIRED,
      PhorgeAuditRequestStatus::CONCERNED,
      PhorgeAuditRequestStatus::ACCEPTED,
      PhorgeAuditRequestStatus::AUDIT_REQUESTED,
    );
    $active = array_fuse($active);

    $viewer_status = $this->getViewerAuditStatus($commit, $viewer);

    return isset($active[$viewer_status]);
  }

  protected function isViewerFullyAccepted(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer) {
    return $this->isViewerAuditStatusFullyAmong(
      $commit,
      $viewer,
      array(
        PhorgeAuditRequestStatus::ACCEPTED,
      ));
  }

  protected function isViewerFullyRejected(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer) {
    return $this->isViewerAuditStatusFullyAmong(
      $commit,
      $viewer,
      array(
        PhorgeAuditRequestStatus::CONCERNED,
      ));
  }

  protected function getViewerAuditStatus(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer) {

    if (!$viewer->getPHID()) {
      return null;
    }

    foreach ($commit->getAudits() as $audit) {
      if ($audit->getAuditorPHID() != $viewer->getPHID()) {
        continue;
      }

      return $audit->getAuditStatus();
    }

    return null;
  }

  protected function isViewerAuditStatusFullyAmong(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer,
    array $status_list) {

    $status = $this->getViewerAuditStatus($commit, $viewer);
    if ($status === null) {
      return false;
    }

    $status_map = array_fuse($status_list);
    foreach ($commit->getAudits() as $audit) {
      if (!$commit->hasAuditAuthority($viewer, $audit)) {
        continue;
      }

      $status = $audit->getAuditStatus();
      if (isset($status_map[$status])) {
        continue;
      }

      return false;
    }

    return true;
  }

  protected function applyAuditorEffect(
    PhorgeRepositoryCommit $commit,
    PhorgeUser $viewer,
    $value,
    $status) {

    $actor = $this->getActor();
    $acting_phid = $this->getActingAsPHID();

    $audits = $commit->getAudits();
    $audits = mpull($audits, null, 'getAuditorPHID');

    $map = array();

    $with_authority = ($status != PhorgeAuditRequestStatus::RESIGNED);
    if ($with_authority) {
      foreach ($audits as $audit) {
        if ($commit->hasAuditAuthority($actor, $audit, $acting_phid)) {
          $map[$audit->getAuditorPHID()] = $status;
        }
      }
    }

    // In all cases, you affect yourself.
    $map[$viewer->getPHID()] = $status;

    $this->updateAudits($commit, $map);
  }

}
