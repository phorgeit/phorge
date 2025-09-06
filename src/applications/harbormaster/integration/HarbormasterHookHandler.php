<?php

abstract class HarbormasterHookHandler
  extends Phobject {

  public static function getHandlers() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getName')
      ->execute();
  }

  public static function getHandler($handler) {
    $base = idx(self::getHandlers(), $handler);

    if ($base) {
      return (clone $base);
    }

    return null;
  }

  abstract public function getName();

  abstract public function handleRequest(AphrontRequest $request);

}
