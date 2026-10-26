<?php

final class PhorgeErrorGeneratingRemarkupRule
  extends PhutilRemarkupRule {

  public static $errorCount = 0;

  public function apply($text) {
    $text = phutil_preg_replace_callback(
      '@#ERRORSTART(.*)ERROREND#@',
      array($this, 'generateError'),
      $text);

    return $text;
  }

  public function generateError(array $matches) {
    $this->getEngine()->storeText('--secret--');
    self::$errorCount++;
    throw new Exception();
  }

}
