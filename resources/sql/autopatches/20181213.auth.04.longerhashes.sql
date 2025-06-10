ALTER TABLE {$NAMESPACE}_user.vixon_session
  CHANGE sessionKey sessionKey VARBINARY(64) NOT NULL;
