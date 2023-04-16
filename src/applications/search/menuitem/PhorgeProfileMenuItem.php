<?php

abstract class PhorgeProfileMenuItem extends Phobject {

  private $viewer;
  private $engine;

  public function getMenuItemTypeIcon() {
    return null;
  }

  abstract public function getMenuItemTypeName();

  abstract public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config);

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array();
  }

  public function canAddToObject($object) {
    return false;
  }

  public function shouldEnableForObject($object) {
    return true;
  }

  public function canHideMenuItem(
    PhorgeProfileMenuItemConfiguration $config) {
    return true;
  }

  public function canMakeDefault(
    PhorgeProfileMenuItemConfiguration $config) {
    return false;
  }

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  public function setEngine(PhorgeProfileMenuEngine $engine) {
    $this->engine = $engine;
    return $this;
  }

  public function getEngine() {
    return $this->engine;
  }

  final public function getMenuItemKey() {
    return $this->getPhobjectClassConstant('MENUITEMKEY');
  }

  final public static function getAllMenuItems() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getMenuItemKey')
      ->execute();
  }

  final protected function newItemView() {
    return new PhorgeProfileMenuItemView();
  }

  public function willGetMenuItemViewList(array $items) {}

  final public function getMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {
    $list = $this->newMenuItemViewList($config);

    if (!is_array($list)) {
      throw new Exception(
        pht(
          'Expected "newMenuItemViewList()" to return a list (in class "%s"), '.
          'but it returned something else ("%s").',
          get_class($this),
          phutil_describe_type($list)));
    }

    assert_instances_of($list, 'PhorgeProfileMenuItemView');

    foreach ($list as $view) {
      $view->setMenuItemConfiguration($config);
    }

    return $list;
  }

  abstract protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config);


  public function newPageContent(
    PhorgeProfileMenuItemConfiguration $config) {
    return null;
  }

  public function getItemViewURI(
    PhorgeProfileMenuItemConfiguration $config) {

    $engine = $this->getEngine();
    $key = $config->getItemIdentifier();

    return $engine->getItemURI("view/{$key}/");
  }

  public function validateTransactions(
    PhorgeProfileMenuItemConfiguration $config,
    $field_key,
    $value,
    array $xactions) {
    return array();
  }

  final protected function isEmptyTransaction($value, array $xactions) {
    $result = $value;
    foreach ($xactions as $xaction) {
      $result = $xaction['new'];
    }

    return !strlen($result);
  }

  final protected function newError($title, $message, $xaction = null) {
    return new PhorgeApplicationTransactionValidationError(
      PhorgeProfileMenuItemConfigurationTransaction::TYPE_PROPERTY,
      $title,
      $message,
      $xaction);
  }

  final protected function newRequiredError($message, $type) {
    $xaction = id(new PhorgeProfileMenuItemConfigurationTransaction())
      ->setMetadataValue('property.key', $type);

    return $this->newError(pht('Required'), $message, $xaction)
      ->setIsMissingFieldError(true);
  }

  final protected function newInvalidError($message, $xaction = null) {
    return $this->newError(pht('Invalid'), $message, $xaction);
  }

  final protected function newEmptyView($title, $message) {
    return id(new PHUIInfoView())
      ->setTitle($title)
      ->setSeverity(PHUIInfoView::SEVERITY_NODATA)
      ->setErrors(
        array(
          $message,
        ));
  }

  public function getAffectedObjectPHIDs(
    PhorgeProfileMenuItemConfiguration $config) {
    return array();
  }

}
