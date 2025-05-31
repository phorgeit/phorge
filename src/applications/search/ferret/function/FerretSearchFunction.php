<?php

abstract class FerretSearchFunction
  extends Phobject {

  /**
   * @return string Ferret function name, e.g. 'title', 'body', 'comment',
   *   'core', 'all'
   */
  abstract public function getFerretFunctionName();
  /**
   * @return string Ferret field key, e.g. 'titl', 'body', 'cmnt', 'core',
   *   'full'
   */
  abstract public function getFerretFieldKey();
  /**
   * @return bool
   */
  abstract public function supportsObject(PhabricatorFerretInterface $object);

  /**
   * @param  string $name
   * @return string Lower-case $name
   */
  final public static function getNormalizedFunctionName($name) {
    return phutil_utf8_strtolower($name);
  }

  /**
   * @param string $function_name
   * @return void
   * @throws Exception if $function_name is invalid
   */
  final public static function validateFerretFunctionName($function_name) {
    if (!preg_match('/^[a-zA-Z-]+\z/', $function_name)) {
      throw new Exception(
        pht(
          'Ferret search engine function name ("%s") is invalid. Function '.
          'names must be nonempty and may only contain latin letters and '.
          'hyphens.',
          $function_name));
    }
  }

  /**
   * @param string $field_key Ferret search engine field key, supposed to be
   *   four characters and only lowercase latin letters
   * @return void
   * @throws Exception if $field_key is invalid
   */
  final public static function validateFerretFunctionFieldKey($field_key) {
    if (!preg_match('/^[a-z]{4}\z/', $field_key)) {
      throw new Exception(
        pht(
          'Ferret search engine field key ("%s") is invalid. Field keys '.
          'must be exactly four characters long and contain only '.
          'lowercase latin letters.',
          $field_key));
    }
  }

  /**
   * @return array<string,FerretSearchFunction>
   */
  final public static function newFerretSearchFunctions() {
    $extensions = PhabricatorFulltextEngineExtension::getAllExtensions();

    $function_map = array();
    $field_map = array();
    $results = array();

    foreach ($extensions as $extension) {
      $functions = $extension->newFerretSearchFunctions();

      if (!is_array($functions)) {
        throw new Exception(
          pht(
            'Expected fulltext engine extension ("%s") to return a '.
            'list of functions from "newFerretSearchFunctions()", '.
            'got "%s".',
            get_class($extension),
            phutil_describe_type($functions)));
      }

      foreach ($functions as $idx => $function) {
        if (!($function instanceof FerretSearchFunction)) {
          throw new Exception(
            pht(
              'Expected fulltext engine extension ("%s") to return a list '.
              'of "FerretSearchFunction" objects from '.
              '"newFerretSearchFunctions()", but found something else '.
              '("%s") at index "%s".',
              get_class($extension),
              phutil_describe_type($function),
              $idx));
        }

        $function_name = $function->getFerretFunctionName();

        self::validateFerretFunctionName($function_name);

        $normal_name = self::getNormalizedFunctionName(
          $function_name);
        if ($normal_name !== $function_name) {
          throw new Exception(
            pht(
              'Ferret function "%s" is specified with a denormalized name. '.
              'Instead, specify the function using the normalized '.
              'function name ("%s").',
              $function_name,
              $normal_name));
        }

        if (isset($function_map[$function_name])) {
          $other_extension = $function_map[$function_name];
          throw new Exception(
            pht(
              'Two different fulltext engine extensions ("%s" and "%s") '.
              'both define a search function with the same name ("%s"). '.
              'Each function must have a unique name.',
              get_class($extension),
              get_class($other_extension),
              $function_name));
        }
        $function_map[$function_name] = $extension;

        $field_key = $function->getFerretFieldKey();

        self::validateFerretFunctionFieldKey($field_key);

        if (isset($field_map[$field_key])) {
          $other_extension = $field_map[$field_key];
          throw new Exception(
            pht(
              'Two different fulltext engine extensions ("%s" and "%s") '.
              'both define a search function with the same key ("%s"). '.
              'Each function must have a unique key.',
              get_class($extension),
              get_class($other_extension),
              $field_key));
        }
        $field_map[$field_key] = $extension;

        $results[$function_name] = $function;
      }
    }

    ksort($results);

    return $results;
  }

}
