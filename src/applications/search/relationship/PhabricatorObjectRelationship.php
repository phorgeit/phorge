<?php

abstract class PhabricatorObjectRelationship extends Phobject {

  private $viewer;
  private $contentSource;

  public function setViewer(PhabricatorUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  public function setContentSource(PhabricatorContentSource $content_source) {
    $this->contentSource = $content_source;
    return $this;
  }

  public function getContentSource() {
    return $this->contentSource;
  }

  final public function getRelationshipConstant() {
    return $this->getPhobjectClassConstant('RELATIONSHIPKEY');
  }

  abstract public function isEnabledForObject($object);

  abstract public function getEdgeConstant();

  /**
   * @return string
   */
  abstract protected function getActionName();

  /**
   * @return string
   */
  abstract protected function getActionIcon();

  /**
   * @return bool
   */
  abstract public function canRelateObjects($src, $dst);

  /**
   * @return string
   */
  abstract public function getDialogTitleText();

  /**
   * @return string
   */
  abstract public function getDialogHeaderText();

  /**
   * @return string
   */
  abstract public function getDialogButtonText();

  /**
   * Display additional instructions at the bottom of the dialog
   * @return string|null
   */
  public function getDialogInstructionsText() {
    return null;
  }

  /**
   * Whether to list the relationship action as a menu item in the
   * "Edit Related Objects" menu in the object's side column
   *
   * @return bool
   */
  public function shouldAppearInActionMenu() {
    return true;
  }

  protected function isActionEnabled($object) {
    $viewer = $this->getViewer();

    return PhabricatorPolicyFilter::hasCapability(
      $viewer,
      $object,
      PhabricatorPolicyCapability::CAN_EDIT);
  }

  public function getRequiredRelationshipCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  final public function newSource() {
    $viewer = $this->getViewer();

    return $this->newRelationshipSource()
      ->setViewer($viewer);
  }

  abstract protected function newRelationshipSource();

  final public function getSourceURI($object) {
    $relationship_key = $this->getRelationshipConstant();
    $object_phid = $object->getPHID();

    return "/search/source/{$relationship_key}/{$object_phid}/";
  }

  final public function newAction($object) {
    $is_enabled = $this->isActionEnabled($object);
    $action_uri = $this->getActionURI($object);

    return id(new PhabricatorActionView())
      ->setName($this->getActionName())
      ->setHref($action_uri)
      ->setIcon($this->getActionIcon())
      ->setDisabled(!$is_enabled)
      ->setWorkflow(true);
  }

  final public static function getAllRelationships() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getRelationshipConstant')
      ->execute();
  }

  private function getActionURI($object) {
    $phid = $object->getPHID();
    $type = $this->getRelationshipConstant();
    return "/search/rel/{$type}/{$phid}/";
  }

  public function getMaximumSelectionSize() {
    return null;
  }

  public function canUndoRelationship() {
    return true;
  }

  public function willUpdateRelationships($object, array $add, array $rem) {
    return array();
  }

  public function didUpdateRelationships($object, array $add, array $rem) {
    return;
  }

}
