<?php

final class TokenGiveConduitAPIMethod extends TokenConduitAPIMethod {

  public function getAPIMethodName() {
    return 'token.give';
  }

  public function getMethodDescription() {
    return pht('Give or change a token.');
  }

  protected function defineParamTypes() {
    return array(
      'tokenPHID'   => 'phid|null',
      'objectPHID'  => 'phid',
    );
  }

  protected function defineReturnType() {
    return 'void';
  }

  protected function defineErrorTypes() {
    return array(
      'ERR-BAD-PHID' => pht(
        'Must pass a PHID for parameter "%s".',
        'objectPHID'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $content_source = $request->newContentSource();
    $phid = $request->getValue('objectPHID');

    if ($phid === null) {
      throw new ConduitException('ERR-BAD-PHID');
    }

    $editor = id(new PhabricatorTokenGivenEditor())
      ->setActor($request->getUser())
      ->setContentSource($content_source);

    if ($request->getValue('tokenPHID')) {
      $editor->addToken(
        $phid,
        $request->getValue('tokenPHID'));
    } else {
      $editor->deleteToken($phid);
    }
  }

}
