<?php

/**
 * @task implementation Job Implementation
 */
final class PhorgeWorkerBulkJob
  extends PhorgeWorkerDAO
  implements
     PhorgePolicyInterface,
     PhorgeSubscribableInterface,
     PhorgeApplicationTransactionInterface,
     PhorgeDestructibleInterface {

  const STATUS_CONFIRM = 'confirm';
  const STATUS_WAITING = 'waiting';
  const STATUS_RUNNING = 'running';
  const STATUS_COMPLETE = 'complete';

  protected $authorPHID;
  protected $jobTypeKey;
  protected $status;
  protected $parameters = array();
  protected $size;
  protected $isSilent;

  private $jobImplementation = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'parameters' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'jobTypeKey' => 'text32',
        'status' => 'text32',
        'size' => 'uint32',
        'isSilent' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_type' => array(
          'columns' => array('jobTypeKey'),
        ),
        'key_author' => array(
          'columns' => array('authorPHID'),
        ),
        'key_status' => array(
          'columns' => array('status'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function initializeNewJob(
    PhorgeUser $actor,
    PhorgeWorkerBulkJobType $type,
    array $parameters) {

    $job = id(new PhorgeWorkerBulkJob())
      ->setAuthorPHID($actor->getPHID())
      ->setJobTypeKey($type->getBulkJobTypeKey())
      ->setParameters($parameters)
      ->attachJobImplementation($type)
      ->setIsSilent(0);

    $job->setSize($job->computeSize());

    return $job;
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeWorkerBulkJobPHIDType::TYPECONST);
  }

  public function getMonitorURI() {
    return '/daemon/bulk/monitor/'.$this->getID().'/';
  }

  public function getManageURI() {
    return '/daemon/bulk/view/'.$this->getID().'/';
  }

  public function getParameter($key, $default = null) {
    return idx($this->parameters, $key, $default);
  }

  public function setParameter($key, $value) {
    $this->parameters[$key] = $value;
    return $this;
  }

  public function loadTaskStatusCounts() {
    $table = new PhorgeWorkerBulkTask();
    $conn_r = $table->establishConnection('r');
    $rows = queryfx_all(
      $conn_r,
      'SELECT status, COUNT(*) N FROM %T WHERE bulkJobPHID = %s
        GROUP BY status',
      $table->getTableName(),
      $this->getPHID());

    return ipull($rows, 'N', 'status');
  }

  public function newContentSource() {
    return PhorgeContentSource::newForSource(
      PhorgeBulkContentSource::SOURCECONST,
      array(
        'jobID' => $this->getID(),
      ));
  }

  public function getStatusIcon() {
    $map = array(
      self::STATUS_CONFIRM => 'fa-question',
      self::STATUS_WAITING => 'fa-clock-o',
      self::STATUS_RUNNING => 'fa-clock-o',
      self::STATUS_COMPLETE => 'fa-check grey',
    );

    return idx($map, $this->getStatus(), 'none');
  }

  public function getStatusName() {
    $map = array(
      self::STATUS_CONFIRM => pht('Confirming'),
      self::STATUS_WAITING => pht('Waiting'),
      self::STATUS_RUNNING => pht('Running'),
      self::STATUS_COMPLETE => pht('Complete'),
    );

    return idx($map, $this->getStatus(), $this->getStatus());
  }

  public function isConfirming() {
    return ($this->getStatus() == self::STATUS_CONFIRM);
  }


/* -(  Job Implementation  )------------------------------------------------- */


  protected function getJobImplementation() {
    return $this->assertAttached($this->jobImplementation);
  }

  public function attachJobImplementation(PhorgeWorkerBulkJobType $type) {
    $this->jobImplementation = $type;
    return $this;
  }

  private function computeSize() {
    return $this->getJobImplementation()->getJobSize($this);
  }

  public function getCancelURI() {
    return $this->getJobImplementation()->getCancelURI($this);
  }

  public function getDoneURI() {
    return $this->getJobImplementation()->getDoneURI($this);
  }

  public function getDescriptionForConfirm() {
    return $this->getJobImplementation()->getDescriptionForConfirm($this);
  }

  public function createTasks() {
    return $this->getJobImplementation()->createTasks($this);
  }

  public function runTask(
    PhorgeUser $actor,
    PhorgeWorkerBulkTask $task) {
    return $this->getJobImplementation()->runTask($actor, $this, $task);
  }

  public function getJobName() {
    return $this->getJobImplementation()->getJobName($this);
  }

  public function getCurtainActions(PhorgeUser $viewer) {
    return $this->getJobImplementation()->getCurtainActions($viewer, $this);
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getAuthorPHID();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

  public function describeAutomaticCapability($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_EDIT:
        return pht('Only the owner of a bulk job can edit it.');
      default:
        return null;
    }
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeWorkerBulkJobEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeWorkerBulkJobTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();

      // We're only removing the actual task objects. This may leave stranded
      // workers in the queue itself, but they'll just flush out automatically
      // when they can't load bulk job data.

      $task_table = new PhorgeWorkerBulkTask();
      $conn_w = $task_table->establishConnection('w');
      queryfx(
        $conn_w,
        'DELETE FROM %T WHERE bulkJobPHID = %s',
        $task_table->getPHID(),
        $this->getPHID());

      $this->delete();
    $this->saveTransaction();
  }


}
