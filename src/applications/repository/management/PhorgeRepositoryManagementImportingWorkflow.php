<?php

final class PhorgeRepositoryManagementImportingWorkflow
  extends PhorgeRepositoryManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('importing')
      ->setExamples('**importing** __repository__ ...')
      ->setSynopsis(
        pht(
          'Show commits in __repository__ which are still importing.'))
      ->setArguments(
        array(
          array(
            'name'        => 'simple',
            'help'        => pht('Show simpler output.'),
          ),
          array(
            'name'        => 'repos',
            'wildcard'    => true,
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $repos = $this->loadRepositories($args, 'repos');

    if (!$repos) {
      throw new PhutilArgumentUsageException(
        pht(
          'Specify one or more repositories to find importing commits for.'));
    }

    $repos = mpull($repos, null, 'getID');

    $table = new PhorgeRepositoryCommit();
    $conn_r = $table->establishConnection('r');

    $rows = queryfx_all(
      $conn_r,
      'SELECT repositoryID, commitIdentifier, importStatus FROM %T
        WHERE repositoryID IN (%Ld)
          AND (importStatus & %d) != %d
          AND (importStatus & %d) != %d',
      $table->getTableName(),
      array_keys($repos),
      PhorgeRepositoryCommit::IMPORTED_ALL,
      PhorgeRepositoryCommit::IMPORTED_ALL,
      PhorgeRepositoryCommit::IMPORTED_UNREACHABLE,
      PhorgeRepositoryCommit::IMPORTED_UNREACHABLE);

    $console = PhutilConsole::getConsole();
    if ($rows) {
      foreach ($rows as $row) {
        $repo = $repos[$row['repositoryID']];
        $identifier = $row['commitIdentifier'];

        $console->writeOut('%s', $repo->formatCommitName($identifier));

        if (!$args->getArg('simple')) {
          $status = $row['importStatus'];
          $need = array();
          if (!($status & PhorgeRepositoryCommit::IMPORTED_MESSAGE)) {
            $need[] = pht('Message');
          }
          if (!($status & PhorgeRepositoryCommit::IMPORTED_CHANGE)) {
            $need[] = pht('Change');
          }
          if (!($status & PhorgeRepositoryCommit::IMPORTED_PUBLISH)) {
            $need[] = pht('Publish');
          }

          $console->writeOut(' %s', implode(', ', $need));
        }

        $console->writeOut("\n");
      }
    } else {
      $console->writeErr(
        "%s\n",
        pht('No importing commits found.'));
    }

    return 0;
  }

}
