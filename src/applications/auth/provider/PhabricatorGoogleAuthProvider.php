<?php

// TM CHANGES BEGIN: Adding JWT imports.

use SimpleJWT\JWT;
use SimpleJWT\Keys\KeySet;
use SimpleJWT\InvalidTokenException;
// TM CHANGES END

final class PhabricatorGoogleAuthProvider
  extends PhabricatorOAuth2AuthProvider {

  public function getProviderName() {
    return pht('Google');
  }

  protected function getProviderConfigurationHelp() {
    $login_uri = PhabricatorEnv::getURI($this->getLoginURI());

    return pht(
      "To configure Google OAuth, create a new 'API Project' here:".
      "\n\n".
      "https://console.developers.google.com/".
      "\n\n".
      "Adjust these configuration settings for your project:".
      "\n\n".
      "  - Under **APIs & auth > APIs**, scroll down the list and enable ".
      "    the **Google+ API**.\n".
      "     - You will need to consent to the **Google+ API** terms if you ".
      " have not before.\n".
      "  - Under **APIs & auth > Credentials**, click **Create New Client".
      "    ID** in the **OAuth** section. Then use these settings:\n".
      "     - **Application Type**: Web Application\n".
      "     - **Authorized Javascript origins**: Leave this empty.\n".
      "     - **Authorized redirect URI**: Set this to `%s`.\n".
      "\n\n".
      "After completing configuration, copy the **Client ID** and ".
      "**Client Secret** from the Google console to the fields above.",
      $login_uri);
  }

  protected function newOAuthAdapter() {
    return new PhutilGoogleAuthAdapter();
  }

  protected function getLoginIcon() {
    return 'Google';
  }

  public function getLoginURI() {
    // TODO: Clean this up. See PhabricatorAuthOldOAuthRedirectController.
    return '/oauth/google/login/';
  }

  // TM CHANGES BEGIN: Adding functions to use Cloud IAP authorization.
  /**
   * The public keys we will download from Google to verify signatures with.
   * @var KeySet
   */
  private $keys;

  const IAP_HEADER = 'x-goog-iap-jwt-assertion';

  public function canAuthRequest(AphrontRequest $request) {
    return $request->getHTTPHeader(PhabricatorGoogleAuthProvider::IAP_HEADER);
  }

  public function processLoginRequest(PhabricatorAuthLoginController $controller) {
    $request = $controller->getRequest();
    if (!$this->canAuthRequest($request)) {
      // Normal login through OAuth
      return parent::processLoginRequest($controller);
    }
    $response = null;
    try {
      $jwt = $this->verifyJWT($request->getHTTPHeader(
          PhabricatorGoogleAuthProvider::IAP_HEADER));
      $email = $jwt->getClaim('email');
      $userid = $jwt->getClaim('sub');
      $this->getAdapter()->setIapAccountData($email, $userid);
      $account = $this->newExternalAccountForIdentifiers($this->getAdapter()->getAccountIdentifiers());
      return array($account, $response);
    } catch (Exception $ex) {
      $response = $controller->buildProviderErrorResponse($this, $ex->getMessage());
      return array(null, $response);
    }
  }

  // Verifies the signed JWT header. Returns the token on success.
  private function verifyJWT($header) {
    if (!$header) {
      throw new Exception('Missing JWT header in request');
    }
    if (!$this->keys) {
      $this->loadKeys();
    }
    try {
      $jwt = JWT::decode($header, $this->keys, 'ES256');
    } catch (InvalidTokenException $e) {
      throw new Exception('Failed to validate JWT: ' . $e->getMessage());
    }
    if ($jwt->getClaim('iss') != 'https://cloud.google.com/iap') {
      throw new Exception('Invalid issuer for JWT');
    }
    return $jwt;
  }

  // Downloads the public keys that we'll verify the JWT with.
  private function loadKeys() {
    $uri = new PhutilURI('https://www.gstatic.com/iap/verify/public_key-jwk');
    $future = new HTTPSFuture($uri);
    list($status, $body) = $future->resolve();
    if ($status->isError()) {
      throw $status;
    }
    $keyset = new KeySet();
    $keyset->load($body);
    $this->keys = $keyset;
  }

  protected function synchronizeOAuthAccount(
    PhabricatorExternalAccount $account) {
      if (!$this->getAdapter()->usingIapLogin()) {
        parent::synchronizeOAuthAccount($account);
      }
  }
  // TM CHANGES END
}
