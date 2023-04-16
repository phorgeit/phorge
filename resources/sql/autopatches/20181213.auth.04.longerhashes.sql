ALTER TABLE {$NAMESPACE}_user.phorge_session
  CHANGE sessionKey sessionKey VARBINARY(64) NOT NULL;
