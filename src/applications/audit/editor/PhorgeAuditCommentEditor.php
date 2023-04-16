<?php

final class PhorgeAuditCommentEditor extends PhorgeEditor {

  public static function getMailThreading(
    PhorgeRepository $repository,
    PhorgeRepositoryCommit $commit) {

    return array(
      'diffusion-audit-'.$commit->getPHID(),
      pht(
        'Commit %s',
        $commit->getMonogram()),
    );
  }

}
