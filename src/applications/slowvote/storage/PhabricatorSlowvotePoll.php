<?php

final class PhabricatorSlowvotePoll
  extends PhabricatorSlowvoteDAO
  implements
    PhabricatorApplicationTransactionInterface,
    PhabricatorPolicyInterface,
    PhabricatorSubscribableInterface,
    PhabricatorFlaggableInterface,
    PhabricatorTokenReceiverInterface,
    PhabricatorProjectInterface,
    PhabricatorDestructibleInterface,
    PhabricatorSpacesInterface,
    PhabricatorConduitResultInterface {

  protected $question;
  protected $description;
  protected $authorPHID;
  protected $responseVisibility;
  protected $shuffle = 0;
  protected $method;
  protected $viewPolicy;
  protected $status;
  protected $spacePHID;

  private $options = self::ATTACHABLE;
  private $choices = self::ATTACHABLE;
  private $viewerChoices = self::ATTACHABLE;

  public static function initializeNewPoll(PhabricatorUser $actor) {
    $app = id(new PhabricatorApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhabricatorSlowvoteApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      PhabricatorSlowvoteDefaultViewCapability::CAPABILITY);

    $default_responses = SlowvotePollResponseVisibility::RESPONSES_VISIBLE;
    $default_method = SlowvotePollVotingMethod::METHOD_PLURALITY;

    return id(new PhabricatorSlowvotePoll())
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($view_policy)
      ->setSpacePHID($actor->getDefaultSpacePHID())
      ->setStatus(SlowvotePollStatus::STATUS_OPEN)
      ->setMethod($default_method)
      ->setResponseVisibility($default_responses);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'question' => 'text255',
        'responseVisibility' => 'text32',
        'shuffle' => 'bool',
        'method' => 'text32',
        'description' => 'text',
        'status' => 'text32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhabricatorSlowvotePollPHIDType::TYPECONST;
  }

  public function getStatusObject() {
    return SlowvotePollStatus::newStatusObject($this->getStatus());
  }

  public function isClosed() {
    return ($this->getStatus() == SlowvotePollStatus::STATUS_CLOSED);
  }

  public function getOptions() {
    return $this->assertAttached($this->options);
  }

  public function attachOptions(array $options) {
    assert_instances_of($options, 'PhabricatorSlowvoteOption');
    $this->options = $options;
    return $this;
  }

  public function getChoices() {
    return $this->assertAttached($this->choices);
  }

  public function attachChoices(array $choices) {
    assert_instances_of($choices, 'PhabricatorSlowvoteChoice');
    $this->choices = $choices;
    return $this;
  }

  public function getViewerChoices(PhabricatorUser $viewer) {
    return $this->assertAttachedKey($this->viewerChoices, $viewer->getPHID());
  }

  public function attachViewerChoices(PhabricatorUser $viewer, array $choices) {
    if ($this->viewerChoices === self::ATTACHABLE) {
      $this->viewerChoices = array();
    }
    assert_instances_of($choices, 'PhabricatorSlowvoteChoice');
    $this->viewerChoices[$viewer->getPHID()] = $choices;
    return $this;
  }

  public function getMonogram() {
    return 'V'.$this->getID();
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }


/* -(  PhabricatorApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhabricatorSlowvoteEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhabricatorSlowvoteTransaction();
  }


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return $this->viewPolicy;
      case PhabricatorPolicyCapability::CAN_EDIT:
        return PhabricatorPolicies::POLICY_NOONE;
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return ($viewer->getPHID() == $this->getAuthorPHID());
  }

  public function describeAutomaticCapability($capability) {
    return pht('The author of a poll can always view and edit it.');
  }



/* -(  PhabricatorSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($phid == $this->getAuthorPHID());
  }


/* -(  PhabricatorTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array($this->getAuthorPHID());
  }

/* -(  PhabricatorDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    $this->openTransaction();
      $choices = id(new PhabricatorSlowvoteChoice())->loadAllWhere(
        'pollID = %d',
        $this->getID());
      foreach ($choices as $choice) {
        $choice->delete();
      }
      $options = id(new PhabricatorSlowvoteOption())->loadAllWhere(
        'pollID = %d',
        $this->getID());
      foreach ($options as $option) {
        $option->delete();
      }
      $this->delete();
    $this->saveTransaction();
  }

/* -(  PhabricatorSpacesInterface  )----------------------------------------- */

  public function getSpacePHID() {
    return $this->spacePHID;
  }

/* -(  PhabricatorConduitResultInterface  )---------------------------------- */

  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhabricatorConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the poll.')),
      id(new PhabricatorConduitSearchFieldSpecification())
        ->setKey('authorPHID')
        ->setType('string')
        ->setDescription(pht('The author of the poll.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getQuestion(),
      'authorPHID' => $this->getAuthorPHID(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

}
