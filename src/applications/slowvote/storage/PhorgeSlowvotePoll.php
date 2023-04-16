<?php

final class PhorgeSlowvotePoll
  extends PhorgeSlowvoteDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeSubscribableInterface,
    PhorgeFlaggableInterface,
    PhorgeTokenReceiverInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeSpacesInterface,
    PhorgeConduitResultInterface {

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

  public static function initializeNewPoll(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeSlowvoteApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      PhorgeSlowvoteDefaultViewCapability::CAPABILITY);

    $default_responses = SlowvotePollResponseVisibility::RESPONSES_VISIBLE;
    $default_method = SlowvotePollVotingMethod::METHOD_PLURALITY;

    return id(new PhorgeSlowvotePoll())
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
    return PhorgeSlowvotePollPHIDType::TYPECONST;
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
    assert_instances_of($options, 'PhorgeSlowvoteOption');
    $this->options = $options;
    return $this;
  }

  public function getChoices() {
    return $this->assertAttached($this->choices);
  }

  public function attachChoices(array $choices) {
    assert_instances_of($choices, 'PhorgeSlowvoteChoice');
    $this->choices = $choices;
    return $this;
  }

  public function getViewerChoices(PhorgeUser $viewer) {
    return $this->assertAttachedKey($this->viewerChoices, $viewer->getPHID());
  }

  public function attachViewerChoices(PhorgeUser $viewer, array $choices) {
    if ($this->viewerChoices === self::ATTACHABLE) {
      $this->viewerChoices = array();
    }
    assert_instances_of($choices, 'PhorgeSlowvoteChoice');
    $this->viewerChoices[$viewer->getPHID()] = $choices;
    return $this;
  }

  public function getMonogram() {
    return 'V'.$this->getID();
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeSlowvoteEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeSlowvoteTransaction();
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
        return $this->viewPolicy;
      case PhorgePolicyCapability::CAN_EDIT:
        return PhorgePolicies::POLICY_NOONE;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return ($viewer->getPHID() == $this->getAuthorPHID());
  }

  public function describeAutomaticCapability($capability) {
    return pht('The author of a poll can always view and edit it.');
  }



/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($phid == $this->getAuthorPHID());
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array($this->getAuthorPHID());
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $choices = id(new PhorgeSlowvoteChoice())->loadAllWhere(
        'pollID = %d',
        $this->getID());
      foreach ($choices as $choice) {
        $choice->delete();
      }
      $options = id(new PhorgeSlowvoteOption())->loadAllWhere(
        'pollID = %d',
        $this->getID());
      foreach ($options as $option) {
        $option->delete();
      }
      $this->delete();
    $this->saveTransaction();
  }

/* -(  PhorgeSpacesInterface  )----------------------------------------- */

  public function getSpacePHID() {
    return $this->spacePHID;
  }

/* -(  PhorgeConduitResultInterface  )---------------------------------- */

  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the poll.')),
      id(new PhorgeConduitSearchFieldSpecification())
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
