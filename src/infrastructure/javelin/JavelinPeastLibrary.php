<?php

final class JavelinPeastLibrary extends Phobject {

  /**
   * The expected Peast version
   *
   * This is the version that would be obtained by downloading and including an
   * up-to-date Peast. The //actual// Peast version may vary.
   */
  const EXPECTED_VERSION = '1.17.4';

  const REPO = 'https://github.com/mck89/peast';

  /**
   * The expected md5 hash of the PHP-parser packages listed above.
   */
  private static $hashes = array(
    // v1.17.4.tar.gz
    'b6fe4a3345ec7653adae94cedb1b5405',
    // v1.17.4.zip
    'e8056c15c52de1dc2a2318462a0a4a61',
  );

  private static $version;

  public static function build() {
    $root = phutil_get_library_root('phorge');
    $path = Filesystem::resolvePath($root.'/../support/peast');
    $target = self::getPath();
    $version = self::EXPECTED_VERSION;

    if (extension_loaded('zip')) {
      $target_path = $path.'/peast-'.$version.'.zip';

      id(new PhutilGitHubReleaseDownloader(self::REPO, $target_path))
        ->setDownloadFormat('zip')
        ->setVersion($version)
        ->validateDownload(self::$hashes)
        ->download();

      $zip = new ZipArchive();
      $result = $zip->open($target_path);
      if (!$result) {
        throw new Exception(
          pht(
            'Opening %s failed! %s.',
            $target_path,
            $result === false ? 'Unknown Error' : (string)$result));
      }

      $zip->extractTo($target);

      // Renames fail if the target directory exists.
      Filesystem::remove("{$target}/Peast");

      Filesystem::rename(
        "{$target}/peast-{$version}/lib/Peast",
        "{$target}/Peast");

      Filesystem::remove("{$target}/peast-{$version}");
    } else if (
      extension_loaded('phar') &&
      extension_loaded('zlib')) {

      $target_path = $path.'/peast-'.$version.'.tar.gz';

      id(new PhutilGitHubReleaseDownloader(self::REPO, $target_path))
        ->setDownloadFormat('tar.gz')
        ->setVersion($version)
        ->validateDownload(self::$hashes)
        ->download();

      id(new PharData($target_path))->extractTo($target, null, true);

      // Renames fail if the target directory exists.
      Filesystem::remove("{$target}/Peast");

      Filesystem::rename(
        "{$target}/peast-{$version}/lib/Peast",
        "{$target}/Peast");

      Filesystem::remove("{$target}/peast-{$version}");
    } else if (Filesystem::binaryExists('git')) {
      execx(
        'git clone --single-branch --depth 1 --branch %s %s %s',
        'v'.self::EXPECTED_VERSION,
        self::REPO,
        $path.'/git');

      // Renames fail if the target directory exists.
      Filesystem::remove("{$path}/Peast");

      Filesystem::rename(
        "{$path}/git/lib/Peast",
        "{$path}/Peast");

      Filesystem::remove($path.'/git');
    } else {
      throw new Exception(
        pht('No viable means to download Peast is available.'));
    }

    Filesystem::writeFile($target.'/version', $version);
  }

  /**
   * Returns human-readable instructions for building PHP-parser.
   *
   * @return string
   */
  public static function getBuildInstructions() {
    $root = phutil_get_library_root('phorge');
    $script = Filesystem::resolvePath(
      $root.'/../support/peast/build-peast.php');

    return phutil_console_format(
      "%s:\n\n  \$ %s\n",
      pht(
        "Your version of '%s' is unbuilt or out of date. Run this ".
        "script to build it.",
        'peast'),
      $script);
  }

  private static function peastAutoloader($classname) {
    $lib = self::getPath();

    if (strncmp($classname, 'Peast', 5)) {
      return false;
    }

    $path = $lib.'/'.str_replace('\\', '/', $classname).'.php';

    if (!Filesystem::pathExists($path)) {
      return false;
    }

    require $path;

    return true;
  }

  public static function loadLibrary() {
    static $loaded = false;

    if (!$loaded) {
      if (!self::isAvailable()) {
        try {
          self::build();
        } catch (Throwable $ex) {
          throw new Exception(self::getBuildInstructions(), 0, $ex);
        }
      }

      spl_autoload_register('JavelinPeastLibrary::peastAutoloader');
    }
  }

  /**
   * Returns the path to the Peast library.
   *
   * @return string
   */
  public static function getPath() {
    static $path = null;

    if (!$path) {
      $root = phutil_get_library_root('phorge');
      $path = Filesystem::resolvePath($root.'/../support/peast/lib');
    }

    return $path;
  }

  /**
   * Returns the Peast version.
   *
   * @return string
   */
  public static function getVersion() {
    if (self::$version === null) {
      $lib = self::getPath();

      if (Filesystem::pathExists($lib.'/version')) {
        self::$version = trim(Filesystem::readFile($lib.'/version'));
      }
    }

    return self::$version;
  }

  /**
   * Checks if PHP-parser is built and up-to-date.
   *
   * @return bool
   */
  public static function isAvailable() {
    $version = self::getVersion();
    return $version === self::EXPECTED_VERSION;
  }

}
