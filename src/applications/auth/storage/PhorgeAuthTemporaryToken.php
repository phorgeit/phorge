<?php

final class PhorgeAuthTemporaryToken extends PhorgeAuthDAO
  implements PhorgePolicyInterface {

  // NOTE: This is usually a PHID, but may be some other kind of resource
  // identifier for some token types.
  protected $tokenResource;
  protected $tokenType;
  protected $tokenExpires;
  protected $tokenCode;
  protected $userPHID;
  protected $properties = array();

  private $isNew = false;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'tokenResource' => 'phid',
        'tokenType' => 'text64',
        'tokenExpires' => 'epoch',
        'tokenCode' => 'text64',
        'userPHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_token' => array(
          'columns' => array('tokenResource', 'tokenType', 'tokenCode'),
          'unique' => true,
        ),
        'key_expires' => array(
          'columns' => array('tokenExpires'),
        ),
        'key_user' => array(
          'columns' => array('userPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  private function newTokenTypeImplementation() {
    $types = PhorgeAuthTemporaryTokenType::getAllTypes();

    $type = idx($types, $this->tokenType);
    if ($type) {
      return clone $type;
    }

    return null;
  }

  public function getTokenReadableTypeName() {
    $type = $this->newTokenTypeImplementation();
    if ($type) {
      return $type->getTokenReadableTypeName($this);
    }

    return $this->tokenType;
  }

  public function isRevocable() {
    if ($this->tokenExpires < time()) {
      return false;
    }

    $type = $this->newTokenTypeImplementation();
    if ($type) {
      return $type->isTokenRevocable($this);
    }

    return false;
  }

  public function revokeToken() {
    if ($this->isRevocable()) {
      $this->setTokenExpires(PhorgeTime::getNow() - 1)->save();
    }
    return $this;
  }

  public static function revokeTokens(
    PhorgeUser $viewer,
    array $token_resources,
    array $token_types) {

    $tokens = id(new PhorgeAuthTemporaryTokenQuery())
      ->setViewer($viewer)
      ->withTokenResources($token_resources)
      ->withTokenTypes($token_types)
      ->withExpired(false)
      ->execute();

    foreach ($tokens as $token) {
      $token->revokeToken();
    }
  }

  public function getTemporaryTokenProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setTemporaryTokenProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function setShouldForceFullSession($force_full) {
    return $this->setTemporaryTokenProperty('force-full-session', $force_full);
  }

  public function getShouldForceFullSession() {
    return $this->getTemporaryTokenProperty('force-full-session', false);
  }

  public function setIsNewTemporaryToken($is_new) {
    $this->isNew = $is_new;
    return $this;
  }

  public function getIsNewTemporaryToken() {
    return $this->isNew;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    // We're just implement this interface to get access to the standard
    // query infrastructure.
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
