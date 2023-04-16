<?php

final class PhorgeMySQLSearchHost
  extends PhorgeSearchHost {

  public function setConfig($config) {
    $this->setRoles(idx($config, 'roles',
      array('read' => true, 'write' => true)));
    return $this;
  }

  public function getDisplayName() {
    return 'MySQL';
  }

  public function getStatusViewColumns() {
    return array(
        pht('Protocol') => 'mysql',
        pht('Roles') => implode(', ', array_keys($this->getRoles())),
    );
  }

  public function getProtocol() {
    return 'mysql';
  }

  public function getHealthRecord() {
    if (!$this->healthRecord) {
      $ref = PhorgeDatabaseRef::getMasterDatabaseRefForApplication(
        'search');
      $this->healthRecord = $ref->getHealthRecord();
    }
    return $this->healthRecord;
  }

  public function getConnectionStatus() {
    PhorgeDatabaseRef::queryAll();
    $ref = PhorgeDatabaseRef::getMasterDatabaseRefForApplication('search');
    $status = $ref->getConnectionStatus();
    return $status;
  }

}
