<?php

final class PhorgeAuthOneTimeLoginTemporaryTokenType
  extends PhorgeAuthTemporaryTokenType {

  const TOKENTYPE = 'login:onetime';

  public function getTokenTypeDisplayName() {
    return pht('One-Time Login');
  }

  public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token) {
    return pht('One-Time Login Token');
  }

  public function isTokenRevocable(PhorgeAuthTemporaryToken $token) {
    return true;
  }

}
