ALTER TABLE {$NAMESPACE}_differential.differential_diff
  DROP COLUMN arcanistProjectPHID;

ALTER TABLE {$NAMESPACE}_differential.differential_revision
  DROP COLUMN arcanistProjectPHID;

DROP TABLE {$NAMESPACE}_repository.repository_arcanistproject;
