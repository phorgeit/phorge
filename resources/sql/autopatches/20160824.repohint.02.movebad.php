<?php

$table = new PhorgeRepositoryCommit();
$conn = $table->establishConnection('w');

$rows = queryfx_all(
  $conn,
  'SELECT fullCommitName FROM repository_badcommit');

$viewer = PhorgeUser::getOmnipotentUser();

foreach ($rows as $row) {
  $identifier = $row['fullCommitName'];

  $commit = id(new DiffusionCommitQuery())
    ->setViewer($viewer)
    ->withIdentifiers(array($identifier))
    ->executeOne();

  if (!$commit) {
    echo tsprintf(
      "%s\n",
      pht(
        'Skipped hint for "%s", this is not a valid commit.',
        $identifier));
  } else {
    PhorgeRepositoryCommitHint::updateHint(
      $commit->getRepository()->getPHID(),
      $commit->getCommitIdentifier(),
      null,
      PhorgeRepositoryCommitHint::HINT_UNREADABLE);

    echo tsprintf(
      "%s\n",
      pht(
        'Updated commit hint for "%s".',
        $identifier));
  }
}
