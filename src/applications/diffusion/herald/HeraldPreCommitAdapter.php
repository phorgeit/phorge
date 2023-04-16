<?php

abstract class HeraldPreCommitAdapter extends HeraldAdapter {

  private $log;
  private $hookEngine;

  abstract public function isPreCommitRefAdapter();

  public function setPushLog(PhorgeRepositoryPushLog $log) {
    $this->log = $log;
    return $this;
  }

  public function setHookEngine(DiffusionCommitHookEngine $engine) {
    $this->hookEngine = $engine;
    return $this;
  }

  public function getHookEngine() {
    return $this->hookEngine;
  }

  public function getAdapterApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  public function isTestAdapterForObject($object) {
    return ($object instanceof PhorgeRepositoryCommit);
  }

  public function canCreateTestAdapterForObject($object) {
    return false;
  }

  public function getAdapterTestDescription() {
    return pht(
      'Commit hook events depend on repository state which is only available '.
      'at push time, and can not be run in test mode.');
  }

  protected function initializeNewAdapter() {
    $this->log = new PhorgeRepositoryPushLog();
  }

  public function isSingleEventAdapter() {
    return true;
  }

  public function getObject() {
    return $this->log;
  }

  public function supportsRuleType($rule_type) {
    switch ($rule_type) {
      case HeraldRuleTypeConfig::RULE_TYPE_GLOBAL:
      case HeraldRuleTypeConfig::RULE_TYPE_OBJECT:
      case HeraldRuleTypeConfig::RULE_TYPE_PERSONAL:
        return true;
      default:
        return false;
    }
  }

  public function canTriggerOnObject($object) {
    if ($object instanceof PhorgeRepository) {
      return true;
    }

    if ($object instanceof PhorgeProject) {
      return true;
    }

    return false;
  }

  public function explainValidTriggerObjects() {
    return pht('This rule can trigger for **repositories** or **projects**.');
  }

  public function getTriggerObjectPHIDs() {
    return array_merge(
      array(
        $this->hookEngine->getRepository()->getPHID(),
        $this->getPHID(),
      ),
      $this->hookEngine->getRepository()->getProjectPHIDs());
  }

  public function supportsWebhooks() {
    return false;
  }

}
