<?php

final class DifferentialInlineCommentEditorTestCase
  extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testDoneStateSurvivesFirstReply() {
    // See T16847. Marking an inline "Done" and publishing the first reply to
    // it in the same submission should leave the inline marked "Done".

    $author = $this->generateNewTestUser();
    $reviewer = $this->generateNewTestUser();

    $diff = $this->newDiff($author);
    $changeset = head($diff->getChangesets());
    $revision = $this->newRevision($author, $diff);

    $parent = $this->newInline($revision, $changeset, $reviewer)
      ->setContent('Please fix this.')
      ->save();
    $this->publishInlines($revision, $reviewer);

    $parent = $this->loadComment($parent->getPHID());
    $this->assertTrue((bool)$parent->getTransactionPHID());
    $this->assertEqual(0, (int)$parent->getHasReplies());

    $parent
      ->setFixedState(PhabricatorInlineComment::STATE_DRAFT)
      ->save();

    $this->newInline($revision, $changeset, $author)
      ->setReplyToCommentPHID($parent->getPHID())
      ->setFixedState(PhabricatorInlineComment::STATE_DRAFT)
      ->setContent('Fixed.')
      ->save();
    $this->publishInlines($revision, $author);

    $parent = $this->loadComment($parent->getPHID());
    $this->assertEqual(
      PhabricatorInlineComment::STATE_DONE,
      $parent->getFixedState());
    $this->assertEqual(1, (int)$parent->getHasReplies());

    $this->assertEqual(
      array(),
      $this->newInlineTransactions($revision, $author));
  }

  private function newDiff(PhabricatorUser $author): DifferentialDiff {
    $raw_diff = <<<EODIFF
diff --git a/src b/src
index 123457..bb216b1 100644
--- a/src
+++ b/src
@@ -1,3 +1,3 @@
 Line a
-Line b
+Line 2
 Line c
EODIFF;

    $parser = new ArcanistDiffParser();
    $diff = DifferentialDiff::newFromRawChanges(
      $author,
      $parser->parseDiff($raw_diff));
    $diff
      ->setAuthorPHID($author->getPHID())
      ->setCreationMethod('test')
      ->setLintStatus(DifferentialLintStatus::LINT_SKIP)
      ->setUnitStatus(DifferentialUnitStatus::UNIT_SKIP)
      ->setDescription('test')
      ->save();

    return $diff;
  }

  private function newRevision(
    PhabricatorUser $author,
    DifferentialDiff $diff): DifferentialRevision {

    $revision = DifferentialRevision::initializeNewRevision($author)
      ->setEditPolicy(PhabricatorPolicies::POLICY_USER)
      ->setHoldAsDraft(true);

    $changes = array(
      PhabricatorTransactions::TYPE_CREATE => null,
      DifferentialRevisionUpdateTransaction::TRANSACTIONTYPE =>
        $diff->getPHID(),
      DifferentialRevisionTitleTransaction::TRANSACTIONTYPE => 'Test',
      DifferentialRevisionSummaryTransaction::TRANSACTIONTYPE => 'Summary',
      DifferentialRevisionTestPlanTransaction::TRANSACTIONTYPE => 'Plan',
    );

    $xactions = array();
    foreach ($changes as $type => $value) {
      $xaction = id(new DifferentialTransaction())
        ->setTransactionType($type);
      if ($value !== null) {
        $xaction->setNewValue($value);
      }
      $xactions[] = $xaction;
    }

    $this->newEditor($author)->applyTransactions($revision, $xactions);

    return $revision;
  }

  private function newInline(
    DifferentialRevision $revision,
    DifferentialChangeset $changeset,
    PhabricatorUser $actor): DifferentialInlineComment {

    return id(new DifferentialInlineComment())
      ->setRevision($revision)
      ->setChangesetID($changeset->getID())
      ->setAuthorPHID($actor->getPHID())
      ->setIsNewFile(1)
      ->setLineNumber(1)
      ->setLineLength(0)
      ->setIsEditing(false);
  }

  private function newInlineTransactions(
    DifferentialRevision $revision,
    PhabricatorUser $actor): array {

    return $this->newEditor($actor)->newAutomaticInlineTransactions(
      $revision,
      DifferentialTransaction::TYPE_INLINE,
      new DifferentialDiffInlineCommentQuery());
  }

  private function publishInlines(
    DifferentialRevision $revision,
    PhabricatorUser $actor) {

    $xactions = $this->newInlineTransactions($revision, $actor);
    $this->assertTrue((bool)$xactions);

    $this->newEditor($actor)->applyTransactions($revision, $xactions);
  }

  private function newEditor(PhabricatorUser $actor):
    DifferentialTransactionEditor {
    return id(new DifferentialTransactionEditor())
      ->setActor($actor)
      ->setContentSource($this->newContentSource())
      ->setIsSilent(true);
  }

  private function loadComment($phid): DifferentialTransactionComment {
    return id(new DifferentialTransactionComment())->loadOneWhere(
      'phid = %s',
      $phid);
  }

}
