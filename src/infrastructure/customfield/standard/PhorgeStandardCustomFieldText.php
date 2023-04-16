<?php

final class PhorgeStandardCustomFieldText
  extends PhorgeStandardCustomField {

  public function getFieldType() {
    return 'text';
  }

  public function buildFieldIndexes() {
    $indexes = array();

    $value = $this->getFieldValue();
    if (strlen($value)) {
      $indexes[] = $this->newStringIndex($value);
    }

    return $indexes;
  }

  public function readApplicationSearchValueFromRequest(
    PhorgeApplicationSearchEngine $engine,
    AphrontRequest $request) {

    return $request->getStr($this->getFieldKey());
  }

  public function applyApplicationSearchConstraintToQuery(
    PhorgeApplicationSearchEngine $engine,
    PhorgeCursorPagedPolicyAwareQuery $query,
    $value) {

    if (strlen($value)) {
      $query->withApplicationSearchContainsConstraint(
        $this->newStringIndex(null),
        $value);
    }
  }

  public function appendToApplicationSearchForm(
    PhorgeApplicationSearchEngine $engine,
    AphrontFormView $form,
    $value) {

    $form->appendChild(
      id(new AphrontFormTextControl())
        ->setLabel($this->getFieldName())
        ->setName($this->getFieldKey())
        ->setValue($value));
  }

  public function shouldAppearInHerald() {
    return true;
  }

  public function getHeraldFieldConditions() {
    return array(
      HeraldAdapter::CONDITION_CONTAINS,
      HeraldAdapter::CONDITION_NOT_CONTAINS,
      HeraldAdapter::CONDITION_IS,
      HeraldAdapter::CONDITION_IS_NOT,
      HeraldAdapter::CONDITION_REGEXP,
      HeraldAdapter::CONDITION_NOT_REGEXP,
    );
  }

  public function getHeraldFieldStandardType() {
    return HeraldField::STANDARD_TEXT;
  }

  protected function getHTTPParameterType() {
    return new AphrontStringHTTPParameterType();
  }

  public function getConduitEditParameterType() {
    return new ConduitStringParameterType();
  }

  protected function newExportFieldType() {
    return new PhorgeStringExportField();
  }

}
