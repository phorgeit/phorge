<?php

final class PhorgeMacroMemeRemarkupControlExtension
  extends PhorgeRemarkupControlExtension {

  const EXTENSIONKEY = 'macromeme';

  public function shouldEnableForObject($object) {
    return function_exists('imagettftext');
  }

  public function buildActions($viewer) {

    $action_code = $this->generateUniqueActionCode();

    Javelin::initBehavior(
      'macro-remarkup-button-meme',
      array('action_code' => $action_code));

    $action = id(new PhorgeRemarkupControlAction())
      ->setActionCode($action_code)
      ->setIcon('fa-meh-o')
      ->setTooltip(pht('Meme'));

    return array(
      PhorgeRemarkupControlAction::newSpacer(),
      $action,
    );
  }

  public function getExtensionApplicationClass() {
    return PhabricatorMacroApplication::class;
  }

}
