<?php

final class PhorgeAuditUpdateOwnersManagementWorkflow
  extends PhorgeAuditManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('update-owners')
      ->setExamples('**update-owners** ...')
      ->setSynopsis(pht('Update package relationships for commits.'))
      ->setArguments(
        array_merge(
          $this->getCommitConstraintArguments(),
          array()));
  }

  public function execute(PhutilArgumentParser $args) {
    $viewer = $this->getViewer();
    $objects = $this->loadCommitsWithConstraints($args);

    foreach ($objects as $object) {
      $commits = $this->loadCommitsForConstraintObject($object);

      foreach ($commits as $commit) {
        $repository = $commit->getRepository();

        $affected_paths = PhorgeOwnerPathQuery::loadAffectedPaths(
          $repository,
          $commit,
          $viewer);

        $affected_packages = PhorgeOwnersPackage::loadAffectedPackages(
          $repository,
          $affected_paths);

        $monograms = mpull($affected_packages, 'getMonogram');
        if ($monograms) {
          $monograms = implode(', ', $monograms);
        } else {
          $monograms = pht('none');
        }

        echo tsprintf(
          "%s\n",
          pht(
            'Updating "%s" (%s)...',
            $commit->getDisplayName(),
            $monograms));

        $commit->writeOwnersEdges(mpull($affected_packages, 'getPHID'));
      }
    }
  }

}
