ALTER TABLE {$NAMESPACE}_user.vixon_session
  DROP PRIMARY KEY;

ALTER TABLE {$NAMESPACE}_user.vixon_session
  ADD id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE {$NAMESPACE}_user.vixon_session
  ADD KEY `key_identity` (userPHID, type);
