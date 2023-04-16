<?php

final class PhorgeSpacesTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testSpacesAnnihilation() {
    $this->destroyAllSpaces();

    // Test that our helper methods work correctly.

    $actor = $this->generateNewTestUser();

    $default = $this->newSpace($actor, pht('Test Space'), true);
    $this->assertEqual(1, count($this->loadAllSpaces()));
    $this->assertEqual(
      1,
      count(PhorgeSpacesNamespaceQuery::getAllSpaces()));
    $cache_default = PhorgeSpacesNamespaceQuery::getDefaultSpace();
    $this->assertEqual($default->getPHID(), $cache_default->getPHID());

    $this->destroyAllSpaces();
    $this->assertEqual(0, count($this->loadAllSpaces()));
    $this->assertEqual(
      0,
      count(PhorgeSpacesNamespaceQuery::getAllSpaces()));
    $this->assertEqual(
      null,
      PhorgeSpacesNamespaceQuery::getDefaultSpace());
  }

  public function testSpacesSeveralSpaces() {
    $this->destroyAllSpaces();

    // Try creating a few spaces, one of which is a default space. This should
    // work fine.

    $actor = $this->generateNewTestUser();
    $default = $this->newSpace($actor, pht('Default Space'), true);
    $this->newSpace($actor, pht('Alternate Space'), false);
    $this->assertEqual(2, count($this->loadAllSpaces()));
    $this->assertEqual(
      2,
      count(PhorgeSpacesNamespaceQuery::getAllSpaces()));

    $cache_default = PhorgeSpacesNamespaceQuery::getDefaultSpace();
    $this->assertEqual($default->getPHID(), $cache_default->getPHID());
  }

  public function testSpacesRequireNames() {
    $this->destroyAllSpaces();

    // Spaces must have nonempty names.

    $actor = $this->generateNewTestUser();

    $caught = null;
    try {
      $options = array(
        'continueOnNoEffect' => true,
      );
      $this->newSpace($actor, '', true, $options);
    } catch (PhorgeApplicationTransactionValidationException $ex) {
      $caught = $ex;
    }

    $this->assertTrue(($caught instanceof Exception));
  }

  public function testSpacesUniqueDefaultSpace() {
    $this->destroyAllSpaces();

    // It shouldn't be possible to create two default spaces.

    $actor = $this->generateNewTestUser();
    $this->newSpace($actor, pht('Default Space'), true);

    $caught = null;
    try {
      $this->newSpace($actor, pht('Default Space #2'), true);
    } catch (AphrontDuplicateKeyQueryException $ex) {
      $caught = $ex;
    }

    $this->assertTrue(($caught instanceof Exception));
  }

  public function testSpacesPolicyFiltering() {
    $this->destroyAllSpaces();

    $creator = $this->generateNewTestUser();
    $viewer = $this->generateNewTestUser();

    // Create a new paste.
    $paste = PhorgePaste::initializeNewPaste($creator)
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setFilePHID('')
      ->setLanguage('')
      ->save();

    // It should be visible.
    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $paste,
        PhorgePolicyCapability::CAN_VIEW));

    // Create a default space with an open view policy.
    $default = $this->newSpace($creator, pht('Default Space'), true)
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->save();
    PhorgeSpacesNamespaceQuery::destroySpacesCache();

    // The paste should now be in the space implicitly, but still visible
    // because the space view policy is open.
    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $paste,
        PhorgePolicyCapability::CAN_VIEW));

    // Make the space view policy restrictive.
    $default
      ->setViewPolicy(PhorgePolicies::POLICY_NOONE)
      ->save();
    PhorgeSpacesNamespaceQuery::destroySpacesCache();

    // The paste should be in the space implicitly, and no longer visible.
    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $paste,
        PhorgePolicyCapability::CAN_VIEW));

    // Put the paste in the space explicitly.
    $paste
      ->setSpacePHID($default->getPHID())
      ->save();
    PhorgeSpacesNamespaceQuery::destroySpacesCache();

    // This should still fail, we're just in the space explicitly now.
    $this->assertFalse(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $paste,
        PhorgePolicyCapability::CAN_VIEW));

    // Create an alternate space with more permissive policies, then move the
    // paste to that space.
    $alternate = $this->newSpace($creator, pht('Alternate Space'), false)
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->save();
    $paste
      ->setSpacePHID($alternate->getPHID())
      ->save();
    PhorgeSpacesNamespaceQuery::destroySpacesCache();

    // Now the paste should be visible again.
    $this->assertTrue(
      PhorgePolicyFilter::hasCapability(
        $viewer,
        $paste,
        PhorgePolicyCapability::CAN_VIEW));
  }

  private function loadAllSpaces() {
    return id(new PhorgeSpacesNamespaceQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->execute();
  }

  private function destroyAllSpaces() {
    PhorgeSpacesNamespaceQuery::destroySpacesCache();
    $spaces = $this->loadAllSpaces();
    foreach ($spaces as $space) {
      $engine = new PhorgeDestructionEngine();
      $engine->destroyObject($space);
    }
  }

  private function newSpace(
    PhorgeUser $actor,
    $name,
    $is_default,
    array $options = array()) {

    $space = PhorgeSpacesNamespace::initializeNewNamespace($actor);

    $type_name =
      PhorgeSpacesNamespaceNameTransaction::TRANSACTIONTYPE;
    $type_default =
      PhorgeSpacesNamespaceDefaultTransaction::TRANSACTIONTYPE;
    $type_view = PhorgeTransactions::TYPE_VIEW_POLICY;
    $type_edit = PhorgeTransactions::TYPE_EDIT_POLICY;

    $xactions = array();

    $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
      ->setTransactionType($type_name)
      ->setNewValue($name);

    $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
      ->setTransactionType($type_view)
      ->setNewValue($actor->getPHID());

    $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
      ->setTransactionType($type_edit)
      ->setNewValue($actor->getPHID());

    if ($is_default) {
      $xactions[] = id(new PhorgeSpacesNamespaceTransaction())
        ->setTransactionType($type_default)
        ->setNewValue($is_default);
    }

    $content_source = $this->newContentSource();

    $editor = id(new PhorgeSpacesNamespaceEditor())
      ->setActor($actor)
      ->setContentSource($content_source);

    if (isset($options['continueOnNoEffect'])) {
      $editor->setContinueOnNoEffect(true);
    }

    $editor->applyTransactions($space, $xactions);

    return $space;
  }

}
