ALTER TABLE {$NAMESPACE}_conduit.conduit_token
  ADD COLUMN tokenName VARCHAR(64) NOT NULL COLLATE utf8mb4_bin;

UPDATE {$NAMESPACE}_conduit.conduit_token
  SET tokenName = "Standard API Token" WHERE tokenType = 'api'
  AND tokenName = "";

UPDATE {$NAMESPACE}_conduit.conduit_token
  SET tokenName = "Command Line API Token" WHERE tokenType = 'cli'
  AND tokenName = "";

UPDATE {$NAMESPACE}_conduit.conduit_token
  SET tokenName = "Cluster API Token" WHERE tokenType = 'clr'
  AND tokenName = "";
