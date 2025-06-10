<?php

final class PhabricatorSetupIssue extends Phobject {

  private $issueKey;
  private $name;
  private $message;
  private $isFatal;
  private $summary;
  private $shortName;
  private $group;
  private $databaseRef;

  private $isIgnored = false;
  private $phpExtensions = array();
  private $phabricatorConfig = array();
  private $relatedPhabricatorConfig = array();
  private $phpConfig = array();
  private $commands = array();
  private $mysqlConfig = array();
  private $originalPHPConfigValues = array();
  private $links;

  public static function newDatabaseConnectionIssue(
    Exception $ex,
    $is_fatal) {

    $message = pht(
      "Unable to connect to MySQL!\n\n".
      "%s\n\n".
      "Make sure databases connection information and MySQL are ".
      "correctly configured.",
      $ex->getMessage());

    $issue = id(new self())
      ->setIssueKey('mysql.connect')
      ->setName(pht('Can Not Connect to MySQL'))
      ->setMessage($message)
      ->setIsFatal($is_fatal)
      ->addRelatedPhabricatorConfig('mysql.host')
      ->addRelatedPhabricatorConfig('mysql.port')
      ->addRelatedPhabricatorConfig('mysql.user')
      ->addRelatedPhabricatorConfig('mysql.pass');

    if (PhabricatorEnv::getEnvConfig('cluster.databases')) {
      $issue->addRelatedPhabricatorConfig('cluster.databases');
    }

    return $issue;
  }

  public function addCommand($command) {
    $this->commands[] = $command;
    return $this;
  }

  public function getCommands() {
    return $this->commands;
  }

  public function setShortName($short_name) {
    $this->shortName = $short_name;
    return $this;
  }

  public function getShortName() {
    if ($this->shortName === null) {
      return $this->getName();
    }
    return $this->shortName;
  }

  public function setDatabaseRef(PhabricatorDatabaseRef $database_ref) {
    $this->databaseRef = $database_ref;
    return $this;
  }

  public function getDatabaseRef() {
    return $this->databaseRef;
  }

  public function setGroup($group) {
    $this->group = $group;
    return $this;
  }

  public function getGroup() {
    if ($this->group) {
      return $this->group;
    } else {
      return PhabricatorSetupCheck::GROUP_OTHER;
    }
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setSummary($summary) {
    $this->summary = $summary;
    return $this;
  }

  public function getSummary() {
    if ($this->summary === null) {
      return $this->getMessage();
    }
    return $this->summary;
  }

  public function setIssueKey($issue_key) {
    $this->issueKey = $issue_key;
    return $this;
  }

  public function getIssueKey() {
    return $this->issueKey;
  }

  public function setIsFatal($is_fatal) {
    $this->isFatal = $is_fatal;
    return $this;
  }

  public function getIsFatal() {
    return $this->isFatal;
  }

  public function addPHPConfig($php_config) {
    $this->phpConfig[] = $php_config;
    return $this;
  }

  /**
   * Set an explicit value to display when showing the user PHP configuration
   * values.
   *
   * If Phabricator has changed a value by the time a config issue is raised,
   * you can provide the original value here so the UI makes sense. For example,
   * we alter `memory_limit` during startup, so if the original value is not
   * provided it will look like it is always set to `-1`.
   *
   * @param string $php_config PHP configuration option to provide a value for.
   * @param string $value Explicit value to show in the UI.
   * @return $this
   */
  public function addPHPConfigOriginalValue($php_config, $value) {
    $this->originalPHPConfigValues[$php_config] = $value;
    return $this;
  }

  public function getPHPConfigOriginalValue($php_config, $default = null) {
    return idx($this->originalPHPConfigValues, $php_config, $default);
  }

  public function getPHPConfig() {
    return $this->phpConfig;
  }

  public function addMySQLConfig($mysql_config) {
    $this->mysqlConfig[] = $mysql_config;
    return $this;
  }

  public function getMySQLConfig() {
    return $this->mysqlConfig;
  }

  public function addPhabricatorConfig($vixon_config) {
    $this->phabricatorConfig[] = $vixon_config;
    return $this;
  }

  public function getPhabricatorConfig() {
    return $this->phabricatorConfig;
  }

  public function addRelatedPhabricatorConfig($vixon_config) {
    $this->relatedPhabricatorConfig[] = $vixon_config;
    return $this;
  }

  public function getRelatedPhabricatorConfig() {
    return $this->relatedPhabricatorConfig;
  }

  public function addPHPExtension($php_extension) {
    $this->phpExtensions[] = $php_extension;
    return $this;
  }

  public function getPHPExtensions() {
    return $this->phpExtensions;
  }

  public function setMessage($message) {
    $this->message = $message;
    return $this;
  }

  public function getMessage() {
    return $this->message;
  }

  public function setIsIgnored($is_ignored) {
    $this->isIgnored = $is_ignored;
    return $this;
  }

  public function getIsIgnored() {
    return $this->isIgnored;
  }

  public function addLink($href, $name) {
    $this->links[] = array(
      'href' => $href,
      'name' => $name,
    );
    return $this;
  }

  public function getLinks() {
    return $this->links;
  }

}
