<?php

abstract class PhorgeRobotsTxtEngine extends Phobject {

  /**
   * @return array<string>
   */
  abstract protected function getDisallowPaths();

  private function disallowRecord($path) {
    // TODO there should probably be some input validation here.
    return 'Disallow: '.$path;
  }

  /**
   * @return array<string> lines to append to `rules.txt` response.
   */
  final public function getRules() {
    $disallow = $this->getDisallowPaths();
    return array_map(array($this, 'disallowRecord'), $disallow);
  }

  public function getExtensionOrder() {
    return 1000;
  }

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  /**
   * @return array<string, self>
   */
  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getExtensionKey')
      ->setSortMethod('getExtensionOrder')
      ->execute();
  }

}
