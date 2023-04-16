<?php

final class PhorgeLegalpadSignaturePolicyRule
  extends PhorgePolicyRule {

  private $signatures = array();

  public function getRuleDescription() {
    return pht('signers of legalpad documents');
  }

  public function willApplyRules(
    PhorgeUser $viewer,
    array $values,
    array $objects) {

    $values = array_unique(array_filter(array_mergev($values)));
    if (!$values) {
      return;
    }

    // TODO: This accepts signature of any version of the document, even an
    // older version.

    $documents = id(new LegalpadDocumentQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs($values)
      ->withSignerPHIDs(array($viewer->getPHID()))
      ->execute();
    $this->signatures = mpull($documents, 'getPHID', 'getPHID');
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    foreach ($value as $document_phid) {
      if (!isset($this->signatures[$document_phid])) {
        return false;
      }
    }

    return true;
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_TOKENIZER;
  }

  public function getValueControlTemplate() {
    return $this->getDatasourceTemplate(new LegalpadDocumentDatasource());
  }

  public function getRuleOrder() {
    return 900;
  }

  public function getValueForStorage($value) {
    PhutilTypeSpec::newFromString('list<string>')->check($value);
    return array_values($value);
  }

  public function getValueForDisplay(PhorgeUser $viewer, $value) {
    $handles = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs($value)
      ->execute();
    return mpull($handles, 'getFullName', 'getPHID');
  }

  public function ruleHasEffect($value) {
    return (bool)$value;
  }

}
