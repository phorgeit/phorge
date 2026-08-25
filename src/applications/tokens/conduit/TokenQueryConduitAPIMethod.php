<?php

final class TokenQueryConduitAPIMethod extends TokenConduitAPIMethod {

  public function getAPIMethodName() {
    return 'token.query';
  }

  public function getMethodDescription() {
    return pht('Query tokens.');
  }

  public function isReadOnlyAPI() {
    return true;
  }

  protected function defineParamTypes() {
    return array();
  }

  protected function defineReturnType() {
    return 'list<dict>';
  }

  protected function execute(ConduitAPIRequest $request) {
    $query = id(new PhabricatorTokenQuery())
      ->setViewer($request->getUser());

    /** @var array<PhabricatorToken> $tokens */
    $tokens = $query->execute();

    return $this->buildTokenDicts($tokens);
  }

}
