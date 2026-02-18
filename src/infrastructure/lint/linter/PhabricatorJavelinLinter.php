<?php

/**
 * @phutil-external-symbol class Peast\Peast
 * @phutil-external-symbol class Peast\Formatter\Compact
 * @phutil-external-symbol class Peast\Syntax\Node\AssignmentExpression
 * @phutil-external-symbol class Peast\Syntax\Node\CallExpression
 * @phutil-external-symbol class Peast\Syntax\Node\MemberExpression
 * @phutil-external-symbol class Peast\Syntax\Node\Identifier
 * @phutil-external-symbol class Peast\Syntax\Node\StringLiteral
 */
final class PhabricatorJavelinLinter extends ArcanistLinter {

  private $symbols = array();

  private $unitTestMode = false;

  const LINT_PRIVATE_ACCESS = 1;
  const LINT_MISSING_DEPENDENCY = 2;
  const LINT_UNNECESSARY_DEPENDENCY = 3;
  const LINT_UNKNOWN_DEPENDENCY = 4;
  const LINT_UNDOCUMENTED_INSTALL = 6;
  const LINT_UNINSTALLED_DOCUMENTATION = 7;

  public function getInfoName() {
    return pht('Javelin Linter');
  }

  public function getInfoDescription() {
    return pht(
      'This linter is intended for use with the Javelin JS library and '.
      'extensions.');
  }

  /**
   * @internal Enables overrides for unit tests.
   *
   * @param bool $unit_tests
   * @return $this
   */
  public function enableUnitTestOverrides(bool $unit_tests) {
    $this->unitTestMode = $unit_tests;
    return $this;
  }

  public function willLintPaths(array $paths) {
    $root = dirname(phutil_get_library_root('phabricator'));
    require_once $root.'/scripts/__init_script__.php';

    JavelinPeastLibrary::loadLibrary();
  }

  public function getLinterName() {
    return 'JAVELIN';
  }

  public function getLinterConfigurationName() {
    return 'javelin';
  }

  public function getLintNameMap() {
    return array(
      self::LINT_PRIVATE_ACCESS =>
        pht('Private Method/Member Access'),
      self::LINT_MISSING_DEPENDENCY =>
        pht('Missing Javelin Dependency'),
      self::LINT_UNNECESSARY_DEPENDENCY =>
        pht('Unnecessary Javelin Dependency'),
      self::LINT_UNKNOWN_DEPENDENCY =>
        pht('Unknown Javelin Dependency'),
      self::LINT_UNDOCUMENTED_INSTALL =>
        pht('Undocumented Install'),
      self::LINT_UNINSTALLED_DOCUMENTATION =>
        pht('Uninstalled Documentation'),
    );
  }

  public function getCacheGranularity() {
    return parent::GRANULARITY_REPOSITORY;
  }

  public function getCacheVersion() {
    $version = '0';
    if (JavelinPeastLibrary::isAvailable()) {
      $version .= '-'.JavelinPeastLibrary::getVersion();
    }
    return $version;
  }

  private function shouldIgnorePath($path) {
    return preg_match('@/__tests__/|externals/javelin/docs/@', $path);
  }

  public function lintPath($path) {
    if ($this->shouldIgnorePath($path)) {
      return;
    }

    $symbols = $this->getUsedAndInstalledSymbolsForPath($path);
    list($uses, $installs, $documented_installs) = $symbols;
    foreach ($uses as $symbol => $line) {
      $parts = explode('.', $symbol);
      foreach ($parts as $part) {
        if ($part[0] == '_' && $part[1] != '_') {
          $base = implode('.', array_slice($parts, 0, 2));
          if (!array_key_exists($base, $installs)) {
            $this->raiseLintAtLine(
              $line,
              0,
              self::LINT_PRIVATE_ACCESS,
              pht(
                "This file accesses private symbol '%s' across file ".
                "boundaries. You may only access private members and methods ".
                "from the file where they are defined.",
                $symbol));
          }
          break;
        }
      }
    }

    $undeclared_installs = array_diff_key($installs, $documented_installs);
    foreach ($undeclared_installs as $name => $line) {
      $this->raiseLintAtLine(
        $line,
        0,
        self::LINT_UNDOCUMENTED_INSTALL,
        pht(
          "This file installs component '%s', but does not document it.",
          $name));
    }

    $uninstalled_declares = array_diff_key($documented_installs, $installs);
    foreach ($uninstalled_declares as $name => $line) {
      $this->raiseLintAtLine(
        $line,
        0,
        self::LINT_UNINSTALLED_DOCUMENTATION,
        pht(
          "This file %s component '%s', but does not actually install it.",
          '@javelin-installs',
          $name));
    }

    $external_classes = array();
    foreach ($uses as $symbol => $line) {
      $parts = explode('.', $symbol);
      $class = implode('.', array_slice($parts, 0, 2));
      if (!array_key_exists($class, $external_classes) &&
          !array_key_exists($class, $installs)) {
        $external_classes[$class] = $line;
      }
    }

    if ($this->unitTestMode) {
      $resources = id(new JavelinCelerityTestResources())
        ->wrap(new CelerityPhabricatorResources());
      $celerity = new CelerityResourceMap($resources);
    } else {
      $celerity = CelerityResourceMap::getNamedInstance('phabricator');
    }

    $path = preg_replace(
      '@^externals/javelinjs/src/@',
      'webroot/rsrc/js/javelin/',
      $path);
    $need = $external_classes;

    if (!strncmp($path, 'webroot/', 8)) {
      $resource_name = substr($path, strlen('webroot/'));
    } else {
      $resource_name = $path;
    }

    $requires = $celerity->getRequiredSymbolsForName($resource_name);
    if (!$requires) {
      $requires = array();
    }

    foreach ($requires as $key => $requires_symbol) {
      $requires_name = $celerity->getResourceNameForSymbol($requires_symbol);
      if ($requires_name === null) {
        $this->raiseLintAtLine(
          1,
          0,
          self::LINT_UNKNOWN_DEPENDENCY,
          pht(
            "This file %s component '%s', but it does not exist. ".
            "You may need to rebuild the Celerity map.",
            '@requires',
            $requires_symbol));
        unset($requires[$key]);
        continue;
      }

      if (preg_match('/\\.css$/', $requires_name)) {
        // If JS requires CSS, just assume everything is fine.
        unset($requires[$key]);
      } else {
        $symbol_path = 'webroot/'.$requires_name;
        list($_, $_, $req_install) = $this->getUsedAndInstalledSymbolsForPath(
          $symbol_path);
        if (array_intersect_key($req_install, $external_classes)) {
          $need = array_diff_key($need, $req_install);
          unset($requires[$key]);
        }
      }
    }

    foreach ($need as $class => $line) {
      $this->raiseLintAtLine(
        $line,
        0,
        self::LINT_MISSING_DEPENDENCY,
        pht(
          "This file uses '%s' but does not @requires the component ".
          "which installs it. You may need to rebuild the Celerity map.",
          $class));
    }

    foreach ($requires as $component) {
      $this->raiseLintAtLine(
        1,
        0,
        self::LINT_UNNECESSARY_DEPENDENCY,
        pht(
          "This file %s component '%s' but does not use anything it provides.",
          '@requires',
          $component));
    }
  }

  private function loadSymbols($path) {
    if (empty($this->symbols[$path])) {
      $this->symbols[$path] = $this->symbolize($path);
    }
    return $this->symbols[$path];
  }

  private function symbolize(string $path): array {
    $installs =  array();
    $behaviors = array();
    $uses =      array();

    $source = $this->getData($path);
    $ast = Peast\Peast::latest($source)->parse();
    $expressions = $ast
      ->query('CallExpression, MemberExpression, AssignmentExpression')
      ->getIterator();

    foreach ($expressions as $expression) {
      if ($expression instanceof Peast\Syntax\Node\CallExpression) {
        $callee = $expression->getCallee();
        if (!($callee instanceof Peast\Syntax\Node\MemberExpression)) {
          continue;
        }

        $member_expression = $this->unnestMemberExpression($callee);
        $full_name = $callee->render(new Peast\Formatter\Compact());
      } else if (
        $expression instanceof Peast\Syntax\Node\AssignmentExpression) {

        $left = $expression->getLeft();
        if (!($left instanceof Peast\Syntax\Node\MemberExpression)) {
          continue;
        }

        $member_expression = $this->unnestMemberExpression($left);
        $full_name = $left->render(new Peast\Formatter\Compact());
      } else {
        $member_expression = $expression;
        $full_name = $expression->render(new Peast\Formatter\Compact());
      }

      $object = $member_expression->getObject();
      $property = $member_expression->getProperty();

      if (
        !($object instanceof Peast\Syntax\Node\Identifier) ||
        !($property instanceof Peast\Syntax\Node\Identifier) ||
        // Computed means using indexers ([ & ]). We ignore these.
        $member_expression->getComputed()) {
        continue;
      }

      if ($object->getName() !== 'JX') {
        continue;
      }

      $property_name = $property->getName();
      $start_line = $expression->getLocation()->getStart()->getLine();
      $end_line = $expression->getLocation()->getEnd()->getLine();

      if ($expression instanceof Peast\Syntax\Node\AssignmentExpression) {
        $installs['JX.'.$property_name] = $end_line;
        continue;
      }

      $uses[$full_name] = $start_line;

      if (
        !($expression instanceof Peast\Syntax\Node\CallExpression) ||
        ($property_name !== 'install' && $property_name !== 'behavior')) {
        continue;
      }

      $arguments = $expression->getArguments();
      if (
        !$arguments ||
        !($arguments[0] instanceof Peast\Syntax\Node\StringLiteral)) {
        continue;
      }

      $name = $arguments[0]->getValue();

      if ($property_name === 'install') {
        $installs['JX.'.$name] = $end_line;
      } else {
        $behaviors[$name] = $end_line;
      }
    }

    return array($installs, $behaviors, $uses);
  }

  /**
   * Unpacks a MemberExpression to its left-most side.
   * MemberExpressions are parsed left to right, but that means the first part
   * is the furthest in the tree.
   *
   * @param Peast\Syntax\Node\MemberExpression $expression
   * @return Peast\Syntax\Node\MemberExpression
   */
  private function unnestMemberExpression(
    Peast\Syntax\Node\MemberExpression $expression) {

    $object = $expression->getObject();
    if ($object instanceof Peast\Syntax\Node\MemberExpression) {
      return $this->unnestMemberExpression($object);
    }

    return $expression;
  }

  private function getUsedAndInstalledSymbolsForPath($path) {
    list($installs, $behaviors, $uses) = $this->loadSymbols($path);
    $documented_installs = array();

    $contents = $this->getData($path);

    $matches = null;
    $count = preg_match_all(
      '/@javelin-installs\W+(\S+)/',
      $contents,
      $matches,
      PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

    if ($count) {
      $engine = $this->getEngine();

      if ($this->unitTestMode) {
        $engine->addFileData($path, $contents);
      }

      foreach ($matches[1] as $match) {
        list($symbol, $offset) = $match;
        list($line) = $engine->getLineAndCharFromOffset($path, $offset);
        $documented_installs[$symbol] = $line + 1;
      }
    }

    return array($uses, $installs, $documented_installs);
  }

}
