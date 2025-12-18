<?php

/**
 * Type describing a generic database name prefix.
 */
final class PhabricatorDatabaseNamePrefixType
  extends PhabricatorTextConfigType {

  const TYPEKEY = 'dbprefix';

  public function validateStoredValue(
    PhabricatorConfigOption $option,
    $value) {

    // A long database prefix can lead to errors, since MySQL and MariaDB
    // only supports databases 64 characters long.
    // https://dev.mysql.com/doc/refman/9.4/en/identifier-length.html
    // The database '_differential' is an example of a long database suffix.
    // It has 13 characters.
    // This means the hard-limit for the prefix is 64 - 13 = 51 characters.
    // Let's stay a bit under this hard limit to stay safe.
    $max_length = 45;
    $len = strlen($value);
    if ($len >= $max_length) {
      throw $this->newException(
        pht(
          'Option "%s" is dangerously long for a database prefix in '.
          'MySQL/MariaDB. The current value is %d characters long. '.
          'It should be less than %d to be safe for future changes.',
          $option->getKey(),
          $len,
          $max_length));
    }

    // MySQL and MariaDB support these characters very well:
    //   [0-9,a-z,A-Z$_]
    // https://dev.mysql.com/doc/refman/8.4/en/identifiers.html
    if (!phutil_preg_match('/^[0-9a-zA-Z$_]+$/', $value)) {
      throw $this->newException(
        pht(
          'Option "%s" only supports numbers, letters, underscores '.
          'and (for some reason) the dollar sign. This is necessary '.
          'to avoid potential MySQL/MariaDB escape issues. '.
          'Remove the invalid characters.',
          $option->getKey()));
    }
  }

}
