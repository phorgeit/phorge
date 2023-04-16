<?php

abstract class DiffusionCommitTransactionType
  extends PhorgeModularTransactionType {

  protected function updateAudits(
    PhorgeRepositoryCommit $commit,
    array $new) {

    $audits = $commit->getAudits();
    $audits = mpull($audits, null, 'getAuditorPHID');

    foreach ($new as $phid => $status) {
      $audit = idx($audits, $phid);
      if (!$audit) {
        $audit = id(new PhorgeRepositoryAuditRequest())
          ->setAuditorPHID($phid)
          ->setCommitPHID($commit->getPHID());

        $audits[$phid] = $audit;
      } else {
        if ($audit->getAuditStatus() === $status) {
          continue;
        }
      }

      $audit
        ->setAuditStatus($status)
        ->save();
    }

    $commit->attachAudits($audits);

    return $audits;
  }

}
