ALTER TABLE {$NAMESPACE}_user.phorge_session
  ADD isPartial BOOL NOT NULL DEFAULT 0;
