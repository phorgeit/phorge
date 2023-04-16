<?php

abstract class PhorgeProjectsBasePolicyRule
  extends PhorgePolicyRule {

  private $memberships = array();

  protected function getMemberships($viewer_phid) {
    return idx($this->memberships, $viewer_phid, array());
  }

  public function willApplyRules(
    PhorgeUser $viewer,
    array $values,
    array $objects) {

    $values = array_unique(array_filter(array_mergev($values)));
    if (!$values) {
      return;
    }

    $projects = id(new PhorgeProjectQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withMemberPHIDs(array($viewer->getPHID()))
      ->withPHIDs($values)
      ->execute();
    foreach ($projects as $project) {
      $this->memberships[$viewer->getPHID()][$project->getPHID()] = true;
    }
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_TOKENIZER;
  }

  public function getValueControlTemplate() {
    $datasource = id(new PhorgeProjectDatasource())
      ->setParameters(
        array(
          'policy' => 1,
        ));

    return $this->getDatasourceTemplate($datasource);
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
