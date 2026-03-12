<?php

/**
 * This linter will aid in properly modularizing Transaction classes.
 * It will mark methods that implementations of PhabricatorModularTransaction
 * should //not// override, so we can make them final.
 */
final class PhorgeModularTransactionLinter
  extends ArcanistLinter {

  const LINT_SHOULD_NOT_OVERRIDE = 1;

  private $xactionClasses = array();

  private static $methods = array(
    'getActionName' => true,
    'getActionStrength' => true,
    'getColor' => true,
    'getIcon' => true,
    'getTitle' => true,
    'getTitleForFeed' => true,
    'newWarningForTransactions' => true,
    'shouldHide' => true,
    'shouldHideForMail' => true,
  );

  public function getInfoName() {
    return pht('Modular Transactions Linter');
  }

  public function getLinterName() {
    return 'MTX';
  }

  public function getLinterConfigurationName() {
    return 'phorge-modular-xact';
  }

  public function willLintPaths(array $paths) {

    $xaction_types = id(new PhutilSymbolLoader())
      ->setAncestorClass(PhabricatorModularTransaction::class)
      ->selectSymbolsWithoutLoading();

    $this->xactionClasses = ipull($xaction_types, 'name', 'name');

  }

  public function getLintNameMap() {
    return array(
      self::LINT_SHOULD_NOT_OVERRIDE => pht('Should not override'),
    );
  }

  public function getLintSeverityMap() {
    return array(
      self::LINT_SHOULD_NOT_OVERRIDE => ArcanistLintSeverity::SEVERITY_WARNING,
    );
  }

  public function lintPath($path) {
    // Just assume the filename is the same as the class name.
    $classname = basename($path, '.php');
    if (!idx($this->xactionClasses, $classname)) {
      return;
    }

    $reflection = new ReflectionClass($classname);

    $class_methods = $reflection->getMethods();
    foreach ($class_methods as $method) {
      if ($method->getDeclaringClass()->getName() != $classname) {
        continue;
      }

      if (!idx(self::$methods, $method->getName())) {
        continue;
      }

      $line = $method->getStartLine();
      if ($line === false) {
        $line = null;
      }

      $message = pht(
        'Implementations of %s should not override method %s. '.
        'This method would eventually become final. '.
        'Use individual implementations of %s to provide each '.
        'transaction type\'s behavior.',
        PhabricatorModularTransaction::class,
        $method->getName(),
        PhabricatorProjectTransactionType::class);

      $this->raiseLintAtLine(
        $line,
        null,
        self::LINT_SHOULD_NOT_OVERRIDE,
        $message);
    }
  }

}
