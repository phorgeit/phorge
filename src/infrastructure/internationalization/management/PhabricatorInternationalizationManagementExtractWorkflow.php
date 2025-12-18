<?php

/**
 * @phutil-external-symbol class PhpParser\NodeTraverser
 * @phutil-external-symbol class PhorgePHPParserExtractor
 */
final class PhabricatorInternationalizationManagementExtractWorkflow
  extends PhabricatorInternationalizationManagementWorkflow {

  const CACHE_VERSION = 1;

  protected function didConstruct() {
    $this
      ->setName('extract')
      ->setExamples(
        '**extract** [__options__] __library__')
      ->setSynopsis(pht('Extract translatable strings.'))
      ->setArguments(
        array(
          array(
            'name' => 'paths',
            'wildcard' => true,
          ),
          array(
            'name' => 'clean',
            'help' => pht('Drop caches before extracting strings. Slow!'),
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();

    $paths = $args->getArg('paths');
    if (!$paths) {
      $paths = array(getcwd());
    }

    $targets = array();
    foreach ($paths as $path) {
      $root = Filesystem::resolvePath($path);

      if (!Filesystem::pathExists($root) || !is_dir($root)) {
        throw new PhutilArgumentUsageException(
          pht(
            'Path "%s" does not exist, or is not a directory.',
            $path));
      }

      $libraries = id(new FileFinder($path))
        ->withPath('*/__phutil_library_init__.php')
        ->find();
      if (!$libraries) {
        throw new PhutilArgumentUsageException(
          pht(
            'Path "%s" contains no libraries.',
            $path));
      }

      foreach ($libraries as $library) {
        $targets[] = Filesystem::resolvePath(dirname($path.'/'.$library)).'/';
      }
    }

    $targets = array_unique($targets);

    foreach ($targets as $library) {
      echo tsprintf(
        "**<bg:blue> %s </bg>** %s\n",
        pht('EXTRACT'),
        pht(
          'Extracting "%s"...',
          Filesystem::readablePath($library)));

      $this->extractLibrary($library);
    }

    return 0;
  }

  private function extractLibrary($root) {
    $files = $this->loadLibraryFiles($root);
    $cache = $this->readCache($root);

    $modified = $this->getModifiedFiles($files, $cache);
    $cache['files'] = $files;

    if ($modified) {
      echo tsprintf(
        "**<bg:blue> %s </bg>** %s\n",
        pht('MODIFIED'),
        pht(
          'Found %s modified file(s) (of %s total).',
          phutil_count($modified),
          phutil_count($files)));

      $old_strings = idx($cache, 'strings');
      $old_strings = array_select_keys($old_strings, $files);
      $new_strings = $this->extractFiles($root, $modified);
      $all_strings = $new_strings + $old_strings;
      $cache['strings'] = $all_strings;

      $this->writeStrings($root, $all_strings);
    } else {
      echo tsprintf(
        "**<bg:blue> %s </bg>** %s\n",
        pht('NOT MODIFIED'),
        pht('Strings for this library are already up to date.'));
    }

    $cache = id(new PhutilJSON())->encodeFormatted($cache);
    $this->writeCache($root, 'i18n_files.json', $cache);
  }

  private function getModifiedFiles(array $files, array $cache) {
    $known = idx($cache, 'files', array());
    $known = array_fuse($known);

    $modified = array();
    foreach ($files as $file => $hash) {

      if (isset($known[$hash])) {
        continue;
      }
      $modified[$file] = $hash;
    }

    return $modified;
  }

  private function extractFiles($root_path, array $files) {
    $hashes = array();

    $bar = id(new PhutilConsoleProgressBar())
      ->setTotal(count($files));

    $messages = array();
    $results = array();

    $parser = PhutilPHPParserLibrary::getParser();
    // Load this class now (once PHP-Parser is built so its base class exists)
    // this is not in the autoloader to avoid errors from tests that try
    // to load every class without installing PHP-Parser
    $root = dirname(phutil_get_library_root('phorge'));
    require_once $root.'/support/php-parser/PhorgePHPParserExtractor.php';
    foreach ($files as $file => $hash) {
      $bar->update(1);
      $full_path = $root_path.DIRECTORY_SEPARATOR.$file;
      $hashes[$full_path] = $hash;
      $file = Filesystem::readablePath($full_path, $root_path);
      try {
        $data = Filesystem::readFile($full_path);
        $tree = $parser->parse($data);
        $visitor = new PhorgePHPParserExtractor($file);
        id(new PhpParser\NodeTraverser($visitor))->traverse($tree);
      } catch (Exception $ex) {
        $messages[] = pht(
          'Failed to parse file "%s": %s',
          $full_path,
          $ex->getMessage());
        continue;
      }
      $results[$hash] = $visitor->getResults();
      $messages += $visitor->getWarnings();
    }

    $bar->done();

    foreach ($messages as $message) {
      echo tsprintf(
        "**<bg:yellow> %s </bg>** %s\n",
        pht('WARNING'),
        $message);
    }

    return $results;
  }

  private function writeStrings($root, array $strings) {
    $map = array();
    foreach ($strings as $hash => $string_list) {
      foreach ($string_list as $string_info) {
        $string = $string_info['string'];

        $map[$string]['uses'][] = array(
          'file' => $string_info['file'],
          'line' => $string_info['line'],
        );

        if (!isset($map[$string]['types'])) {
          $map[$string]['types'] = $string_info['types'];
        } else if ($map[$string]['types'] !== $string_info['types']) {
          echo tsprintf(
            "**<bg:yellow> %s </bg>** %s\n",
            pht('WARNING'),
            pht(
              'Inferred types for string "%s" vary across callsites.',
              $string_info['string']));
        }
      }
    }

    ksort($map);

    $json = id(new PhutilJSON())->encodeFormatted($map);
    $this->writeCache($root, 'i18n_strings.json', $json);
  }

  private function loadLibraryFiles($root) {
    $files = id(new FileFinder($root))
      ->withType('f')
      ->withSuffix('php')
      ->excludePath('*/.*')
      ->excludePath('*/__tests__/*')
      ->setGenerateChecksums(true)
      ->find();

    $map = array();
    foreach ($files as $file => $hash) {
      $file = Filesystem::readablePath($file, $root);
      $file = ltrim($file, '/');

      if (dirname($file) == '.') {
        continue;
      }

      if (dirname($file) == 'extensions') {
        continue;
      }

      $map[$file] = md5($hash.$file);
    }

    return $map;
  }

  private function readCache($root) {
    $path = $this->getCachePath($root, 'i18n_files.json');

    $default = array(
      'version' => self::CACHE_VERSION,
      'files' => array(),
      'strings' => array(),
    );

    if ($this->getArgv()->getArg('clean')) {
      return $default;
    }

    if (!Filesystem::pathExists($path)) {
      return $default;
    }

    try {
      $data = Filesystem::readFile($path);
    } catch (Exception $ex) {
      return $default;
    }

    try {
      $cache = phutil_json_decode($data);
    } catch (PhutilJSONParserException $e) {
      return $default;
    }

    $version = idx($cache, 'version');
    if ($version !== self::CACHE_VERSION) {
      return $default;
    }

    return $cache;
  }

  private function writeCache($root, $file, $data) {
    $path = $this->getCachePath($root, $file);

    $cache_dir = dirname($path);
    if (!Filesystem::pathExists($cache_dir)) {
      Filesystem::createDirectory($cache_dir, 0755, true);
    }

    Filesystem::writeFile($path, $data);
  }

  private function getCachePath($root, $to_file) {
    return $root.'/.cache/'.$to_file;
  }

}
