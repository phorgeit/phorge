CREATE TABLE {$NAMESPACE}_policy.policy_namedpolicytransaction_comment (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  phid VARBINARY(64) NOT NULL,
  transactionPHID VARBINARY(64),
  commentVersion INT(10) UNSIGNED NOT NULL,
  authorPHID VARBINARY(64) NOT NULL,
  viewPolicy VARBINARY(64) NOT NULL,
  editPolicy VARBINARY(64) NOT NULL,
  content LONGTEXT NOT NULL COLLATE {$COLLATE_TEXT},
  contentSource LONGTEXT NOT NULL COLLATE {$COLLATE_TEXT},
  isDeleted BOOL NOT NULL,
  dateCreated INT UNSIGNED NOT NULL,
  dateModified INT UNSIGNED NOT NULL,
  UNIQUE KEY `key_version` ( `transactionPHID`, `commentVersion` ),
  UNIQUE KEY `key_phid` ( `phid` )
) ENGINE=InnoDB, COLLATE {$COLLATE_TEXT};
