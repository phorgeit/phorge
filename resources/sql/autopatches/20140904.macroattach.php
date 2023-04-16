<?php

$table = new PhorgeFileImageMacro();
foreach (new LiskMigrationIterator($table) as $macro) {
  $name = $macro->getName();

  echo pht("Linking macro '%s'...", $name)."\n";

  $editor = new PhorgeEdgeEditor();

  $phids[] = $macro->getFilePHID();
  $phids[] = $macro->getAudioPHID();
  $phids = array_filter($phids);

  if ($phids) {
    foreach ($phids as $phid) {
      $editor->addEdge(
        $macro->getPHID(),
        25,
        $phid);
    }
    $editor->save();
  }
}

echo pht('Done.')."\n";
