<?php

final class PhabricatorStorageManagementQuickstartWorkflow
  extends PhabricatorStorageManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('quickstart')
      ->setExamples('**quickstart** [__options__]')
      ->setSynopsis(
        pht(
          'Generate a new quickstart database dump. This command is mostly '.
          'useful for internal development.'))
      ->setArguments(
        array(
          array(
            'name'  => 'output',
            'param' => 'file',
            'help'  => pht('Specify output file to write.'),
          ),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    parent::execute($args);

    $output = $args->getArg('output');
    if (!$output) {
      throw new PhutilArgumentUsageException(
        pht(
          'Specify a file to write with `%s`.',
          '--output'));
    }

    $namespace = 'vixon_quickstart_'.Filesystem::readRandomCharacters(8);

    $bin = dirname(phutil_get_library_root('phabricator')).'/bin/storage';

    // We don't care which database we're using to generate a quickstart file,
    // since all of the schemata should be identical.
    $api = $this->getAnyAPI();

    $ref = $api->getRef();
    $ref_key = $ref->getRefKey();

    if (!$api->isCharacterSetAvailable('utf8mb4')) {
      throw new PhutilArgumentUsageException(
        pht(
          'You can only generate a new quickstart file if MySQL supports '.
          'the %s character set (available in MySQL 5.5 and newer). The '.
          'configured server does not support %s.',
          'utf8mb4',
          'utf8mb4'));
    }

    $err = phutil_passthru(
      '%s upgrade --force --no-quickstart --namespace %s --ref %s',
      $bin,
      $namespace,
      $ref_key);
    if ($err) {
      return $err;
    }

    $err = phutil_passthru(
      '%s adjust --force --namespace %s --ref %s',
      $bin,
      $namespace,
      $ref_key);
    if ($err) {
      return $err;
    }

    $tmp = new TempFile();
    $err = phutil_passthru(
      '%s dump --namespace %s --ref %s > %s',
      $bin,
      $namespace,
      $ref_key,
      $tmp);
    if ($err) {
      return $err;
    }

    $err = phutil_passthru(
      '%s destroy --force --namespace %s --ref %s',
      $bin,
      $namespace,
      $ref_key);
    if ($err) {
      return $err;
    }

    $dump = Filesystem::readFile($tmp);

    $dump = str_replace(
      $namespace,
      '{$NAMESPACE}',
      $dump);

    // NOTE: This is a hack. We can not use `binary` for these columns, because
    // they are a part of a fulltext index. This regex is avoiding matching a
    // possible NOT NULL at the end of the line.
    $old = $dump;
    $dump = preg_replace(
      '/`corpus` longtext CHARACTER SET .*? COLLATE [^\s,]+/mi',
      '`corpus` longtext CHARACTER SET {$CHARSET_FULLTEXT} '.
        'COLLATE {$COLLATE_FULLTEXT}',
      $dump);
    if ($dump == $old) {
      // If we didn't make any changes, yell about it. We'll produce an invalid
      // dump otherwise.
      throw new PhutilArgumentUsageException(
        pht(
          'Failed to apply hack to adjust %s search column!',
          'FULLTEXT'));
    }

    $dump = str_replace(
      'utf8mb4_bin',
      '{$COLLATE_TEXT}',
      $dump);

    $dump = str_replace(
      'utf8mb4_unicode_ci',
      '{$COLLATE_SORT}',
      $dump);

    $dump = str_replace(
      'utf8mb4',
      '{$CHARSET}',
      $dump);

    $old = $dump;
    $dump = preg_replace(
      '/CHARACTER SET {\$CHARSET} COLLATE {\$COLLATE_SORT}/mi',
      'CHARACTER SET {$CHARSET_SORT} COLLATE {$COLLATE_SORT}',
      $dump);
    if ($dump == $old) {
      throw new PhutilArgumentUsageException(
        pht('Failed to adjust SORT columns!'));
    }

    // Strip out a bunch of unnecessary commands which make the dump harder
    // to handle and slower to import.

    // Remove character set adjustments and key disables.
    $dump = preg_replace(
      '(^/\*.*\*/;$)m',
      '',
      $dump);

    // Remove comments.
    $dump = preg_replace('/^--.*$/m', '', $dump);

    // Remove table drops, locks, and unlocks. These are never relevant when
    // performing a quickstart.
    $dump = preg_replace(
      '/^(DROP TABLE|LOCK TABLES|UNLOCK TABLES).*$/m',
      '',
      $dump);

    // Collapse adjacent newlines.
    $dump = preg_replace('/\n\s*\n/', "\n", $dump);

    $dump = str_replace(';', ";\n", $dump);
    $dump = trim($dump)."\n";

    Filesystem::writeFile($output, $dump);

    $console = PhutilConsole::getConsole();
    $console->writeOut(
      "**<bg:green> %s </bg>** %s\n",
      pht('SUCCESS'),
      pht('Wrote fresh quickstart SQL.'));

    return 0;
  }

}
