<?php

final class TokenGiveConduitAPIMethod extends TokenConduitAPIMethod {

  public function getAPIMethodName() {
    return 'token.give';
  }

  public function getMethodDescription() {
    return pht('Give or change or remove a token.');
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
      'ERR-BAD-OBJECTPHID' => pht(
        'Must pass a valid PHID for parameter "%s".',
        'objectPHID'),
      'ERR-BAD-TOKENPHID' => pht(
        'Must pass a valid PHID for parameter "%s".',
        'tokenPHID'),
    );
  }

  protected function execute(ConduitAPIRequest $request) {
    $content_source = $request->newContentSource();
    $object_phid = $request->getValue('objectPHID');

    $invalid = PhabricatorObjectQuery::loadInvalidPHIDsForViewer(
      $request->getUser(),
      array($object_phid));
    if ($invalid) {
      throw new ConduitException('ERR-BAD-OBJECTPHID');
    }


    $editor = id(new PhabricatorTokenGivenEditor())
      ->setActor($request->getUser())
      ->setContentSource($content_source);

    if ($request->getValue('tokenPHID')) {
      $token_phid = $request->getValue('tokenPHID');
      $invalid = PhabricatorObjectQuery::loadInvalidPHIDsForViewer(
        $request->getUser(),
        array($token_phid));
      if ($invalid) {
        throw new ConduitException('ERR-BAD-TOKENPHID');
      }

      $editor->addToken(
        $object_phid,
        $token_phid);
    } else {
      $editor->deleteToken($object_phid);
    }
  }

}
