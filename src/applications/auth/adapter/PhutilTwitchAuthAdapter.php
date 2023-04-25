<?php

/**
 * Authentication adapter for Twitch.tv OAuth2.
 */
final class PhutilTwitchAuthAdapter extends PhutilOAuthAuthAdapter {

  public function getAdapterType() {
    return 'twitch';
  }

  public function getAdapterDomain() {
    return 'twitch.tv';
  }

  public function getAccountID() {
    return $this->getOAuthAccountData('id');
  }

  public function getAccountEmail() {
    return $this->getOAuthAccountData('email');
  }

  public function getAccountName() {
    return $this->getOAuthAccountData('login');
  }

  public function getAccountImageURI() {
    return $this->getOAuthAccountData('profile_image_url');
  }

  public function getAccountURI() {
    $name = $this->getAccountName();
    if ($name) {
      return 'http://www.twitch.tv/'.$name;
    }
    return null;
  }

  public function getAccountRealName() {
    return $this->getOAuthAccountData('display_name');
  }

  protected function getAuthenticateBaseURI() {
    return 'https://id.twitch.tv/oauth2/authorize';
  }

  protected function getTokenBaseURI() {
    return 'https://id.twitch.tv/oauth2/token';
  }

  public function getScope() {
    return 'user_read';
  }

  public function getExtraAuthenticateParameters() {
    return array(
      'response_type' => 'code',
    );
  }

  public function getExtraTokenParameters() {
    return array(
      'grant_type' => 'authorization_code',
    );
  }

  protected function loadOAuthAccountData() {
    return id(new PhutilTwitchFuture())
      ->setClientID($this->getClientID())
      ->setAccessToken($this->getAccessToken())
      ->setRawTwitchQuery('users')
      ->resolve();
  }

}
