ALTER TABLE {$NAMESPACE}_user.phorge_session
  ADD UNIQUE KEY `key_phid` (phid);
