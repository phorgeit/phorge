<?php

final class PhabricatorMySQLSearchHost
  extends PhabricatorSearchHost {

  public function setConfig($config) {
    $this->setRoles(idx($config, 'roles',
      array('read' => true, 'write' => true)));
    return $this;
  }

  /**
   * @return string Display name of the search host: "MySQL"
   */
  public function getDisplayName() {
    return 'MySQL';
  }

  /**
   * @return string[] Get a list of fields to show in the status overview UI
   */
  public function getStatusViewColumns() {
    return array(
        pht('Protocol') => 'mysql',
        pht('Roles') => implode(', ', array_keys($this->getRoles())),
    );
  }

  /**
   * @return string Search host protocol: "mysql"
   */
  public function getProtocol() {
    return 'mysql';
  }

  public function getHealthRecord() {
    if (!$this->healthRecord) {
      $ref = PhabricatorDatabaseRef::getMasterDatabaseRefForApplication(
        'search');
      $this->healthRecord = $ref->getHealthRecord();
    }
    return $this->healthRecord;
  }

  public function getConnectionStatus() {
    PhabricatorDatabaseRef::queryAll();
    $ref = PhabricatorDatabaseRef::getMasterDatabaseRefForApplication('search');
    $status = $ref->getConnectionStatus();
    return $status;
  }

}
