<?php

abstract class TokenConduitAPIMethod extends ConduitAPIMethod {

  final public function getApplication() {
    return PhabricatorApplication::getByClass(
      PhabricatorTokensApplication::class);
  }

  public function getMethodStatus() {
    return self::METHOD_STATUS_UNSTABLE;
  }

  /**
   * @param array<PhabricatorToken> $tokens
   */
  public function buildTokenDicts(array $tokens) {
    assert_instances_of($tokens, PhabricatorToken::class);

    $list = array();
    foreach ($tokens as $token) {
      $list[] = array(
        'id' => $token->getID(),
        'name' => $token->getName(),
        'phid' => $token->getPHID(),
      );
    }

    return $list;
  }

  /**
   * @param array<PhabricatorTokenGiven> $tokens_given
   */
  public function buildTokenGivenDicts(array $tokens_given) {
    assert_instances_of($tokens_given, PhabricatorTokenGiven::class);

    $list = array();
    foreach ($tokens_given as $given) {
      $list[] = array(
        'authorPHID'  => $given->getAuthorPHID(),
        'objectPHID'  => $given->getObjectPHID(),
        'tokenPHID'   => $given->getTokenPHID(),
        'dateCreated' => $given->getDateCreated(),
      );
    }

    return $list;
  }

}
