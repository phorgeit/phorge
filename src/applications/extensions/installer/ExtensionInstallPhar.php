<?php

final class ExtensionInstallPhar extends PhorgeExtensionInstallerStrategy {

  protected function fetchContent($source): string {
    $this->assertCanUsePhar();
    $extension_dir = $this->getInstallDir();

    Filesystem::assertExists($source);

    $target_filename =
      Filesystem::resolvePath(basename($source), $extension_dir);

    if (Filesystem::pathExists($target_filename)) {

      throw new ArcanistUsageException(
        pht(
          'Installing this would require copying to location %s, but that '.
          'file already exists.',
          $target_filename));
    }

    $actual_dir = 'phar://';
    $actual_dir .= $target_filename;
    $actual_dir .= '/src';

    $console = PhutilConsole::getConsole();
    if ($this->isDryRun()) {
      $console->writeOut(
        "%s\n",
        pht(
          'Would copy `%s` to `%s` and add `%s` to LoadLibraries',
          $source,
          $target_filename,
          $actual_dir));
    } else {
      $console->writeOut(
        "%s\n",
        pht('Copying `%s` to `%s`', $source, $target_filename));
      Filesystem::copyFile($source, $target_filename);
    }

    return $actual_dir;
  }

  protected static function assertCanUsePhar() {
    static $good = null;

    $min_version = '8.0';
    if ($good === null) {
      $cur_version = phpversion();
      if (version_compare($cur_version, $min_version, '<')) {
        $good = false;
      } else {
        $good = true;
      }
    }
    if ($good) {
      return;
    }
    throw new Exception(
      pht(
        'PHP versions older then %s have known security vulnerabilities '.
        'when considering PHAR files; Installing extensions from PHAR files '.
        'and from the Store is therefore disabled. See %s',
        $min_version,
        'https://wiki.php.net/rfc/phar_stop_autoloading_metadata'));
  }

}
