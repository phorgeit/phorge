<?php

echo pht('Migrating project members to edges...')."\n";
$table = new PhorgeProject();
$table->establishConnection('w');

foreach (new LiskMigrationIterator($table) as $proj) {
  $id = $proj->getID();
  echo pht('Project %d: ', $id);

  $members = queryfx_all(
    $proj->establishConnection('w'),
    'SELECT userPHID FROM %T WHERE projectPHID = %s',
    'project_affiliation',
    $proj->getPHID());

  if (!$members) {
    echo "-\n";
    continue;
  }

  $members = ipull($members, 'userPHID');

  $editor = new PhorgeEdgeEditor();
  foreach ($members as $user_phid) {
    $editor->addEdge(
      $proj->getPHID(),
      PhorgeProjectProjectHasMemberEdgeType::EDGECONST,
      $user_phid);
  }
  $editor->save();
  echo pht('OKAY')."\n";
}

echo pht('Done.')."\n";
