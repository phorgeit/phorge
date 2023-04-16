<?php

final class PhorgePolicyDataTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testProjectPolicyMembership() {
    $author = $this->generateNewTestUser();

    $proj_a = PhorgeProject::initializeNewProject($author)
      ->setName('A')
      ->save();
    $proj_b = PhorgeProject::initializeNewProject($author)
      ->setName('B')
      ->save();

    $proj_a->setViewPolicy($proj_b->getPHID())->save();
    $proj_b->setViewPolicy($proj_a->getPHID())->save();

    $user = new PhorgeUser();

    $results = id(new PhorgeProjectQuery())
      ->setViewer($user)
      ->execute();

    $this->assertEqual(0, count($results));
  }

  public function testCustomPolicyRuleUser() {
    $user_a = $this->generateNewTestUser();
    $user_b = $this->generateNewTestUser();
    $author = $this->generateNewTestUser();

    $policy = id(new PhorgePolicy())
      ->setRules(
        array(
          array(
            'action' => PhorgePolicy::ACTION_ALLOW,
            'rule' => 'PhorgeUsersPolicyRule',
            'value' => array($user_a->getPHID()),
          ),
        ))
      ->save();

    $task = ManiphestTask::initializeNewTask($author);
    $task->setViewPolicy($policy->getPHID());
    $task->save();

    $can_a_view = PhorgePolicyFilter::hasCapability(
      $user_a,
      $task,
      PhorgePolicyCapability::CAN_VIEW);

    $this->assertTrue($can_a_view);

    $can_b_view = PhorgePolicyFilter::hasCapability(
      $user_b,
      $task,
      PhorgePolicyCapability::CAN_VIEW);

    $this->assertFalse($can_b_view);
  }

  public function testCustomPolicyRuleAdministrators() {
    $user_a = $this->generateNewTestUser();
    $user_a->setIsAdmin(true)->save();
    $user_b = $this->generateNewTestUser();
    $author = $this->generateNewTestUser();

    $policy = id(new PhorgePolicy())
      ->setRules(
        array(
          array(
            'action' => PhorgePolicy::ACTION_ALLOW,
            'rule' => 'PhorgeAdministratorsPolicyRule',
            'value' => null,
          ),
        ))
      ->save();

    $task = ManiphestTask::initializeNewTask($author);
    $task->setViewPolicy($policy->getPHID());
    $task->save();

    $can_a_view = PhorgePolicyFilter::hasCapability(
      $user_a,
      $task,
      PhorgePolicyCapability::CAN_VIEW);

    $this->assertTrue($can_a_view);

    $can_b_view = PhorgePolicyFilter::hasCapability(
      $user_b,
      $task,
      PhorgePolicyCapability::CAN_VIEW);

    $this->assertFalse($can_b_view);
  }

  public function testCustomPolicyRuleLunarPhase() {
    $user_a = $this->generateNewTestUser();
    $author = $this->generateNewTestUser();

    $policy = id(new PhorgePolicy())
      ->setRules(
        array(
          array(
            'action' => PhorgePolicy::ACTION_ALLOW,
            'rule' => 'PhorgeLunarPhasePolicyRule',
            'value' => 'new',
          ),
        ))
      ->save();

    $task = ManiphestTask::initializeNewTask($author);
    $task->setViewPolicy($policy->getPHID());
    $task->save();

    $time_a = PhorgeTime::pushTime(934354800, 'UTC');

      $can_a_view = PhorgePolicyFilter::hasCapability(
        $user_a,
        $task,
        PhorgePolicyCapability::CAN_VIEW);
      $this->assertTrue($can_a_view);

    unset($time_a);


    $time_b = PhorgeTime::pushTime(1116745200, 'UTC');

      $can_a_view = PhorgePolicyFilter::hasCapability(
        $user_a,
        $task,
        PhorgePolicyCapability::CAN_VIEW);
      $this->assertFalse($can_a_view);

    unset($time_b);
  }

  public function testObjectPolicyRuleTaskAuthor() {
    $author = $this->generateNewTestUser();
    $viewer = $this->generateNewTestUser();

    $rule = new ManiphestTaskAuthorPolicyRule();

    $task = ManiphestTask::initializeNewTask($author);
    $task->setViewPolicy($rule->getObjectPolicyFullKey());
    $task->save();

    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $author,
        $task,
        PhorgePolicyCapability::CAN_VIEW));

    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $task,
        PhorgePolicyCapability::CAN_VIEW));
  }

  public function testObjectPolicyRuleThreadMembers() {
    $author = $this->generateNewTestUser();
    $viewer = $this->generateNewTestUser();

    $rule = new ConpherenceThreadMembersPolicyRule();

    $thread = ConpherenceThread::initializeNewRoom($author);
    $thread->setViewPolicy($rule->getObjectPolicyFullKey());
    $thread->save();

    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $author,
        $thread,
        PhorgePolicyCapability::CAN_VIEW));

    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $thread,
        PhorgePolicyCapability::CAN_VIEW));

    $participant = id(new ConpherenceParticipant())
      ->setParticipantPHID($viewer->getPHID())
      ->setConpherencePHID($thread->getPHID());

    $thread->attachParticipants(array($viewer->getPHID() => $participant));

    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $thread,
        PhorgePolicyCapability::CAN_VIEW));
  }

  public function testObjectPolicyRuleSubscribers() {
    $author = $this->generateNewTestUser();

    $rule = new PhorgeSubscriptionsSubscribersPolicyRule();

    $task = ManiphestTask::initializeNewTask($author);
    $task->setViewPolicy($rule->getObjectPolicyFullKey());
    $task->save();

    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $author,
        $task,
        PhorgePolicyCapability::CAN_VIEW));

    id(new PhorgeSubscriptionsEditor())
      ->setActor($author)
      ->setObject($task)
      ->subscribeExplicit(array($author->getPHID()))
      ->save();

    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $author,
        $task,
        PhorgePolicyCapability::CAN_VIEW));
  }

}
