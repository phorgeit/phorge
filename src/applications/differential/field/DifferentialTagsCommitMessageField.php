<?php

final class DifferentialTagsCommitMessageField
  extends DifferentialCommitMessageField {

  const FIELDKEY = 'phorge:projects';

  public function getFieldName() {
    return pht('Tags');
  }

  public function getFieldOrder() {
    return 7000;
  }

  public function getFieldAliases() {
    return array(
      'Tag',
      'Project',
      'Projects',
    );
  }

  public function isTemplateField() {
    return false;
  }

  public function parseFieldValue($value) {
    return $this->parseObjectList(
      $value,
      array(
        PhorgeProjectProjectPHIDType::TYPECONST,
      ));
  }

  public function readFieldValueFromObject(DifferentialRevision $revision) {
    if (!$revision->getPHID()) {
      return array();
    }

    $projects = PhorgeEdgeQuery::loadDestinationPHIDs(
      $revision->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
    $projects = array_reverse($projects);

    return $projects;
  }

  public function readFieldValueFromConduit($value) {
    return $this->readStringListFieldValueFromConduit($value);
  }

  public function renderFieldValue($value) {
    return $this->renderHandleList($value);
  }

  public function getFieldTransactions($value) {
    return array(
      array(
        'type' => PhorgeProjectsEditEngineExtension::EDITKEY_SET,
        'value' => $value,
      ),
    );
  }

}
