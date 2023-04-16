<?php

final class PhorgeOAuthServerClient
  extends PhorgeOAuthServerDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface {

  protected $secret;
  protected $name;
  protected $redirectURI;
  protected $creatorPHID;
  protected $isTrusted;
  protected $viewPolicy;
  protected $editPolicy;
  protected $isDisabled;

  public function getEditURI() {
    $id = $this->getID();
    return "/oauthserver/edit/{$id}/";
  }

  public function getViewURI() {
    $id = $this->getID();
    return "/oauthserver/client/view/{$id}/";
  }

  public static function initializeNewClient(PhorgeUser $actor) {
    return id(new PhorgeOAuthServerClient())
      ->setCreatorPHID($actor->getPHID())
      ->setSecret(Filesystem::readRandomCharacters(32))
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setEditPolicy($actor->getPHID())
      ->setIsDisabled(0)
      ->setIsTrusted(0);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
        'secret' => 'text32',
        'redirectURI' => 'text255',
        'isTrusted' => 'bool',
        'isDisabled' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'creatorPHID' => array(
          'columns' => array('creatorPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeOAuthServerClientPHIDType::TYPECONST);
  }

  public function getURI() {
    return urisprintf(
      '/oauthserver/client/view/%d/',
      $this->getID());
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeOAuthServerEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeOAuthServerTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $this->delete();

      $authorizations = id(new PhorgeOAuthClientAuthorization())
        ->loadAllWhere('clientPHID = %s', $this->getPHID());
      foreach ($authorizations as $authorization) {
        $authorization->delete();
      }

      $tokens = id(new PhorgeOAuthServerAccessToken())
        ->loadAllWhere('clientPHID = %s', $this->getPHID());
      foreach ($tokens as $token) {
        $token->delete();
      }

      $codes = id(new PhorgeOAuthServerAuthorizationCode())
        ->loadAllWhere('clientPHID = %s', $this->getPHID());
      foreach ($codes as $code) {
        $code->delete();
      }

    $this->saveTransaction();

  }
}
