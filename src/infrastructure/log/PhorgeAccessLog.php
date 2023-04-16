<?php

final class PhorgeAccessLog extends Phobject {

  private static $log;

  public static function init() {
    // NOTE: This currently has no effect, but some day we may reuse PHP
    // interpreters to run multiple requests. If we do, it has the effect of
    // throwing away the old log.
    self::$log = null;
  }

  public static function getLog() {
    if (!self::$log) {
      $path = PhorgeEnv::getEnvConfig('log.access.path');
      $format = PhorgeEnv::getEnvConfig('log.access.format');
      $format = nonempty(
        $format,
        "[%D]\t%p\t%h\t%r\t%u\t%C\t%m\t%U\t%R\t%c\t%T");

      // NOTE: Path may be null. We still create the log, it just won't write
      // anywhere.

      $log = id(new PhutilDeferredLog($path, $format))
        ->setFailQuietly(true)
        ->setData(
          array(
            'D' => date('r'),
            'h' => php_uname('n'),
            'p' => getmypid(),
            'e' => time(),
            'I' => PhorgeEnv::getEnvConfig('cluster.instance'),
          ));

      self::$log = $log;
    }

    return self::$log;
  }

}
