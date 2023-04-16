<?php

final class PhorgeAuthMFASyncTemporaryTokenType
  extends PhorgeAuthTemporaryTokenType {

  const TOKENTYPE = 'mfa.sync';
  const DIGEST_KEY = 'mfa.sync';

  public function getTokenTypeDisplayName() {
    return pht('MFA Sync');
  }

  public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token) {
    return pht('MFA Sync Token');
  }

}
