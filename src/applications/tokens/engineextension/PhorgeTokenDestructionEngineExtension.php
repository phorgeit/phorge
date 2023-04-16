<?php

final class PhorgeTokenDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'tokens';

  public function getExtensionName() {
    return pht('Tokens');
  }

  public function canDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return ($object instanceof PhorgeTokenReceiverInterface);
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $tokens = id(new PhorgeTokenGiven())->loadAllWhere(
      'objectPHID = %s',
      $object->getPHID());

    foreach ($tokens as $token) {
      $token->delete();
    }
  }

}
