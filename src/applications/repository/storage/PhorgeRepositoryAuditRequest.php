<?php

final class PhorgeRepositoryAuditRequest
  extends PhorgeRepositoryDAO
  implements PhorgePolicyInterface {

  protected $auditorPHID;
  protected $commitPHID;
  protected $auditReasons = array();
  protected $auditStatus;

  private $commit = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_SERIALIZATION => array(
        'auditReasons' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'auditStatus' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'commitPHID' => array(
          'columns' => array('commitPHID'),
        ),
        'auditorPHID' => array(
          'columns' => array('auditorPHID', 'auditStatus'),
        ),
        'key_unique' => array(
          'columns' => array('commitPHID', 'auditorPHID'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function isUser() {
    $user_type = PhorgePeopleUserPHIDType::TYPECONST;
    return (phid_get_type($this->getAuditorPHID()) == $user_type);
  }

  public function attachCommit(PhorgeRepositoryCommit $commit) {
    $this->commit = $commit;
    return $this;
  }

  public function getCommit() {
    return $this->assertAttached($this->commit);
  }

  public function isResigned() {
    return $this->getAuditRequestStatusObject()->isResigned();
  }

  public function getAuditRequestStatusObject() {
    $status = $this->getAuditStatus();
    return PhorgeAuditRequestStatus::newForStatus($status);
  }

/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return $this->getCommit()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return $this->getCommit()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return pht(
      'This audit is attached to a commit, and inherits its policies.');
  }
}
