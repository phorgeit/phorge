<?php

final class PhorgeOAuth1SecretTemporaryTokenType
  extends PhorgeAuthTemporaryTokenType {

  const TOKENTYPE = 'oauth1:request:secret';

  public function getTokenTypeDisplayName() {
    return pht('OAuth1 Handshake Secret');
  }

  public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token) {
    return pht('OAuth1 Handshake Token');
  }

}
