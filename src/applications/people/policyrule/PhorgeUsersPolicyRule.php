<?php

final class PhorgeUsersPolicyRule extends PhorgePolicyRule {

  public function getRuleDescription() {
    return pht('users');
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    foreach ($value as $phid) {
      if ($phid == $viewer->getPHID()) {
        return true;
      }
    }

    return false;
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_TOKENIZER;
  }

  public function getValueControlTemplate() {
    return $this->getDatasourceTemplate(new PhorgePeopleDatasource());
  }

  public function getRuleOrder() {
    return 100;
  }

  public function getValueForStorage($value) {
    PhutilTypeSpec::newFromString('list<string>')->check($value);
    return array_values($value);
  }

  public function getValueForDisplay(PhorgeUser $viewer, $value) {
    if (!$value) {
      return array();
    }

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
