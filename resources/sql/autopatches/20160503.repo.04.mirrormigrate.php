<?php

$table = new PhorgeRepository();
$conn_w = $table->establishConnection('w');

$mirrors = queryfx_all(
  $conn_w,
  'SELECT * FROM %T',
  'repository_mirror');

foreach ($mirrors as $mirror) {
  $repository_phid = $mirror['repositoryPHID'];
  $uri = $mirror['remoteURI'];

  $already_exists = id(new PhorgeRepositoryURI())->loadOneWhere(
    'repositoryPHID = %s AND uri = %s',
    $repository_phid,
    $uri);
  if ($already_exists) {
    // Decline to migrate stuff that looks like it was already migrated.
    continue;
  }

  $new_uri = PhorgeRepositoryURI::initializeNewURI()
    ->setIOType(PhorgeRepositoryURI::IO_MIRROR)
    ->setRepositoryPHID($repository_phid)
    ->setURI($uri)
    ->setCredentialPHID($mirror['credentialPHID'])
    ->setDateCreated($mirror['dateCreated'])
    ->setDateModified($mirror['dateModified'])
    ->save();

  echo tsprintf(
    "%s\n",
    pht(
      'Migrated mirror "%s".',
      $uri));
}
