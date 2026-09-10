<?php

abstract class PhorgeRemarkupControlExtension extends Phobject {

  /** @return PhorgeRemarkupControlAction|null */
  public function buildAction(PhabricatorUser $viewer) {
    throw new PhutilMethodNotImplementedException(
      pht(
        'Implement either `%s` or `%s` in class %s!',
        'buildAction()',
        'buildActions()',
        get_class($this)));
  }

  /** @return array<PhorgeRemarkupControlAction> */
  public function buildActions(PhabricatorUser $viewer) {
    $action = $this->buildAction($viewer);

    if ($action !== null) {
      return array($action);
    }

    return array();
  }

  public function shouldEnableForObject(?object $object) {
    return true;
  }

  abstract public function getExtensionApplicationClass();

  public function getExtensionOrder() {
    return 1000;
  }

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final protected function generateUniqueActionCode() {
    static $counter = 0;
    return '__prcea'.($counter++);
  }

  /**
   * @return array<string, self>
   */
  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getExtensionKey')
      ->setSortMethod('getExtensionOrder')
      ->execute();
  }

  public static function buildExtensionActions(
    PhabricatorUser $viewer,
    $object) {

    $extensions = self::getAllExtensions();

    $extensions =
      PhabricatorApplication::filterExtensionsByInstalledApplication(
        $extensions,
        'getExtensionApplicationClass',
        $viewer);

    foreach ($extensions as $key => $extension) {
      if (!$extension->shouldEnableForObject($object)) {
        unset($extensions[$key]);
      }
    }

    $result = array();

    foreach ($extensions as $key => $extension) {
      $actions = $extension->buildActions($viewer);

      foreach ($actions as $index => $action) {
        if (!($action instanceof PhorgeRemarkupControlAction)) {
          throw new Exception(
            pht(
              '%s extension ("%s", of class "%s") returned a list of '.
              'Remarkup actions from "%s" that contains an invalid value: '.
              'a value (with key "%s") is not an object of class "%s". ',
              self::class,
              $key,
              get_class($extension),
              'buildActions()',
              $index,
              'PhorgeRemarkupControlAction'));
        }

        if (!$action->getIsSpacer()) {
          if ($action->getHref() == '#' && $action->getActionCode() == null) {
            throw new Exception(
              pht(
                'A %s must have either an valid HREF url or an action-name. '.
                'Extension %s built an action with neither (with key %s). ',
                'PhorgeRemarkupControlAction',
                get_class($extension),
                $index));
          }

        }

        $result[] = $action;
      }
    }

    return $result;
  }

}
