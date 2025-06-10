ALTER TABLE {$NAMESPACE}_user.user ADD UNIQUE KEY (phid);
ALTER TABLE {$NAMESPACE}_user.vixon_session ADD UNIQUE KEY (sessionKey);
