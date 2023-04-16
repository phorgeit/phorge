ALTER TABLE {$NAMESPACE}_user.phorge_session
  ADD phid VARBINARY(64) NOT NULL;
