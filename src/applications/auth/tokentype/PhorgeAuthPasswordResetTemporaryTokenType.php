<?php

final class PhorgeAuthPasswordResetTemporaryTokenType
  extends PhorgeAuthTemporaryTokenType {

  const TOKENTYPE = 'login:password';

  public function getTokenTypeDisplayName() {
    return pht('Password Reset');
  }

  public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token) {
    return pht('Password Reset Token');
  }

  public function isTokenRevocable(PhorgeAuthTemporaryToken $token) {
    return true;
  }

}
