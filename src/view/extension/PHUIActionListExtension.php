<?php

abstract class PHUIActionListExtension extends Phobject {

  abstract public function shouldEnableForObject($object);

  abstract public function getExtensionApplicationClass();

  /**
   * @return PhabricatorActionView|null
   */
  abstract protected function buildAction();

  private $viewer;
  private $object;

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer(): PhabricatorUser {
    return $this->viewer;
  }

  public function setObject($object) {
    $this->object = $object;
    return $this;
  }

  public function getObject() {
    return $this->object;
  }


  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  public function getExtensionOrder() {
    return 1000;
  }

  /**
   * @return array<PhabricatorActionView>
   */
  protected function buildActions() {
    $panel = $this->buildAction();

    if ($panel !== null) {
      return array($panel);
    }

    return array();
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
    foreach ($extensions as $extension) {
      $extension->setViewer($viewer);
      $extension->setObject($object);
    }

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
      $actions = $extension->buildActions();
      if (!is_array($actions)) {
        throw new Exception(
          pht(
            'ActionList extension ("%s", of class "%s") did not return a list '
            .'of ActionView from method "%s". This method must return an '.
            'array, and each value in the array must be a "%s" object.',
            $key,
            get_class($extension),
            'buildCurtainPanels()',
            'PhabricatorActionView'));
      }

      foreach ($actions as $action_key => $action) {
        if (!($action instanceof PhabricatorActionView)) {
          throw new Exception(
            pht(
              'ActionList extension ("%s", of class "%s") returned a list of '.
              'ActionList extension from "%s" that contains an invalid value: '.
              'a value (with key "%s") is not an object of class "%s". ',
              $key,
              get_class($extension),
              'buildCurtainPanels()',
              $action_key,
              'PhabricatorActionView'));
        }

        $result[] = $action;
      }
    }

    return $result;
  }

}
