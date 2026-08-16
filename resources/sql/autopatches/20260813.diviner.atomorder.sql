ALTER TABLE {$NAMESPACE}_diviner.diviner_livesymbol
  ADD COLUMN `order`
    INT(10) UNSIGNED
    NOT NULL
    DEFAULT 1000
    AFTER `title`;
ALTER TABLE {$NAMESPACE}_diviner.diviner_livesymbol
  ALTER COLUMN `order` DROP DEFAULT;
