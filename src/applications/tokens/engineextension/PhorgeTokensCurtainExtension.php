<?php

final class PhorgeTokensCurtainExtension
  extends PHUICurtainExtension {

  const EXTENSIONKEY = 'tokens.tokens';

  public function shouldEnableForObject($object) {
    return ($object instanceof PhorgeTokenReceiverInterface);
  }

  public function getExtensionApplication() {
    return new PhorgeTokensApplication();
  }

  public function buildCurtainPanel($object) {
    $viewer = $this->getViewer();

    $tokens_given = id(new PhorgeTokenGivenQuery())
      ->setViewer($viewer)
      ->withObjectPHIDs(array($object->getPHID()))
      ->execute();
    if (!$tokens_given) {
      return null;
    }

    $author_phids = mpull($tokens_given, 'getAuthorPHID');
    $handles = $viewer->loadHandles($author_phids);

    Javelin::initBehavior('phorge-tooltips');

    $list = array();
    foreach ($tokens_given as $token_given) {
      $token = $token_given->getToken();

      $aural = javelin_tag(
        'span',
        array(
          'aural' => true,
        ),
        pht(
          '"%s" token, awarded by %s.',
          $token->getName(),
          $handles[$token_given->getAuthorPHID()]->getName()));

      $list[] = javelin_tag(
        'span',
        array(
          'sigil' => 'has-tooltip',
          'class' => 'token-icon',
          'meta' => array(
            'tip' => $handles[$token_given->getAuthorPHID()]->getName(),
          ),
        ),
        array(
          $aural,
          $token->renderIcon(),
        ));
    }

    return $this->newPanel()
      ->setHeaderText(pht('Tokens'))
      ->setOrder(30000)
      ->appendChild($list);
  }

}
