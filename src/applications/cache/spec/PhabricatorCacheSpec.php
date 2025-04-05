<?php

abstract class PhabricatorCacheSpec extends Phobject {

  private $name;
  private $isEnabled = false;
  private $version;
  private $clearCacheCallback = null;
  private $issues = array();

  private $usedMemory = 0;
  private $totalMemory = 0;
  private $entryCount = null;

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setIsEnabled($is_enabled) {
    $this->isEnabled = $is_enabled;
    return $this;
  }

  public function getIsEnabled() {
    return $this->isEnabled;
  }

  public function setVersion($version) {
    $this->version = $version;
    return $this;
  }

  public function getVersion() {
    return $this->version;
  }

  protected function newIssue($key) {
    $issue = id(new PhabricatorSetupIssue())
      ->setIssueKey($key);
    $this->issues[$key] = $issue;

    return $issue;
  }

  public function getIssues() {
    return $this->issues;
  }

  public function setUsedMemory($used_memory) {
    $this->usedMemory = $used_memory;
    return $this;
  }

  public function getUsedMemory() {
    return $this->usedMemory;
  }

  public function setTotalMemory($total_memory) {
    $this->totalMemory = $total_memory;
    return $this;
  }

  public function getTotalMemory() {
    return $this->totalMemory;
  }

  public function setEntryCount($entry_count) {
    $this->entryCount = $entry_count;
    return $this;
  }

  public function getEntryCount() {
    return $this->entryCount;
  }

  protected function raiseEnableAPCIssue() {
    $summary = pht('Enabling APCu will improve performance.');
    $message = pht(
      'The APCu PHP extension is installed, but not enabled in your '.
      'PHP configuration. Enabling this extension will improve performance. '.
      'Edit the "%s" setting to enable this extension.',
      'apc.enabled');

    return $this
      ->newIssue('extension.apc.enabled')
      ->setShortName(pht('APCu Disabled'))
      ->setName(pht('APCu Extension Not Enabled'))
      ->setSummary($summary)
      ->setMessage($message)
      ->addPHPConfig('apc.enabled');
  }

  public function setClearCacheCallback($callback) {
    $this->clearCacheCallback = $callback;
    return $this;
  }

  public function getClearCacheCallback() {
    return $this->clearCacheCallback;
  }
}
