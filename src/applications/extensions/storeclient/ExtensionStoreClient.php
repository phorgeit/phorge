<?php

final class ExtensionStoreClient
  extends Phobject {

  const METHOD = 'extensionstore.queryextension';

  private $conduitClient;

  public function __construct(string $conduit_uri) {
    $this->conduitClient = new ConduitClient($conduit_uri);
  }

  public function setApiToken($token) {
    $this->conduitClient->setConduitToken($token);
    return $this;
  }

  // TODO multiplex

  public function queryExtension(string $extension_key) {
    $params = array(
      'extensionKeys' => array($extension_key),
    );

    $results = $this->conduitClient
      ->callMethodSynchronous(self::METHOD,
      $params);

    $result = idx($results, $extension_key);
    if (!$result) {
      return null; // throw?
    }

    return ExtensionQueryResult::fromDictionary($result);
  }

}
