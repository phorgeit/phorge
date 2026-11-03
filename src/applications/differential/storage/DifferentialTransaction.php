<?php

final class DifferentialTransaction
  extends PhabricatorModularTransaction {

  private $isCommandeerSideEffect;

  /** @deprecated see DifferentialRevisionInlineTransaction */
  const TYPE_INLINE  = 'differential:inline';

  /** @deprecated see T16867 */
  const TYPE_ACTION  = 'differential:action';

  const MAILTAG_REVIEWERS      = 'differential-reviewers';
  const MAILTAG_CLOSED         = 'differential-committed';
  const MAILTAG_CC             = 'differential-cc';
  const MAILTAG_COMMENT        = 'differential-comment';
  const MAILTAG_UPDATED        = 'differential-updated';
  const MAILTAG_REVIEW_REQUEST = 'differential-review-request';
  const MAILTAG_OTHER          = 'differential-other';

  public function getBaseTransactionClass() {
    return DifferentialRevisionTransactionType::class;
  }

  protected function newFallbackModularTransactionType() {
    // TODO: This allows us to render modern strings for older transactions
    // without doing a migration. At some point, we should do a migration and
    // throw this away.

    // NOTE: Old reviewer edits are raw edge transactions. They could be
    // migrated to modular transactions when the rest of this migrates.

    $xaction_type = $this->getTransactionType();
    if ($xaction_type == PhabricatorTransactions::TYPE_CUSTOMFIELD) {
      switch ($this->getMetadataValue('customfield:key')) {
        case 'differential:title':
          return new DifferentialRevisionTitleTransaction();
        case 'differential:test-plan':
          return new DifferentialRevisionTestPlanTransaction();
        case 'differential:repository':
          return new DifferentialRevisionRepositoryTransaction();
      }
    }

    return parent::newFallbackModularTransactionType();
  }


  public function setIsCommandeerSideEffect($is_side_effect) {
    $this->isCommandeerSideEffect = $is_side_effect;
    return $this;
  }

  public function getIsCommandeerSideEffect() {
    return $this->isCommandeerSideEffect;
  }

  public function getApplicationName() {
    return 'differential';
  }

  public function getApplicationTransactionType() {
    return DifferentialRevisionPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new DifferentialTransactionComment();
  }

  public function isInlineCommentTransaction() {
    switch ($this->getTransactionType()) {
      case DifferentialRevisionInlineTransaction::TRANSACTIONTYPE:
        return true;
    }

    return parent::isInlineCommentTransaction();
  }

  public function getMailTags() {
    $tags = array();

    switch ($this->getTransactionType()) {
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
        $tags[] = self::MAILTAG_CC;
        break;
      case DifferentialRevisionUpdateTransaction::TRANSACTIONTYPE:
        $old = $this->getOldValue();
        if ($old === null) {
          $tags[] = self::MAILTAG_REVIEW_REQUEST;
        } else {
          $tags[] = self::MAILTAG_UPDATED;
        }
        break;
      case PhabricatorTransactions::TYPE_COMMENT:
      case DifferentialRevisionInlineTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case DifferentialRevisionReviewersTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_REVIEWERS;
        break;
      case DifferentialRevisionCloseTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_CLOSED;
        break;
    }

    if (!$tags) {
      $tags[] = self::MAILTAG_OTHER;
    }

    return $tags;
  }

  public function renderExtraInformationLink() {
    if ($this->getMetadataValue('revisionMatchData')) {
      $details_href =
        '/differential/revision/closedetails/'.$this->getPHID().'/';
      $details_link = javelin_tag(
        'a',
        array(
          'href' => $details_href,
          'sigil' => 'workflow',
        ),
        pht('Explain Why'));
      return $details_link;
    }
    return parent::renderExtraInformationLink();
  }

  public function shouldDisplayGroupWith(array $group) {

    // Never group status changes with other types of actions, they're indirect
    // and don't make sense when combined with direct actions.

    if ($this->isStatusTransaction($this)) {
      return false;
    }

    foreach ($group as $xaction) {
      if ($this->isStatusTransaction($xaction)) {
        return false;
      }
    }

    return parent::shouldDisplayGroupWith($group);
  }

  private function isStatusTransaction($xaction) {
    $status_type = DifferentialRevisionStatusTransaction::TRANSACTIONTYPE;
    if ($xaction->getTransactionType() == $status_type) {
      return true;
    }

    return false;
  }

  public function renderAsTextForDoorkeeper(
    DoorkeeperFeedStoryPublisher $publisher,
    PhabricatorFeedStory $story,
    array $xactions) {

    $body = parent::renderAsTextForDoorkeeper($publisher, $story, $xactions);

    $inline_type = DifferentialRevisionInlineTransaction::TRANSACTIONTYPE;
    $inlines = array();
    foreach ($xactions as $xaction) {
      if ($xaction->getTransactionType() == $inline_type) {
        $inlines[] = $xaction;
      }
    }

    // TODO: This is a bit gross, but far less bad than it used to be. It
    // could be further cleaned up at some point.

    if ($inlines) {
      $engine = PhabricatorMarkupEngine::newMarkupEngine(array())
        ->setConfig('viewer', new PhabricatorUser())
        ->setMode(PhutilRemarkupEngine::MODE_TEXT);

      $body .= "\n\n";
      $body .= pht('Inline Comments');
      $body .= "\n";

      $changeset_ids = array();
      foreach ($inlines as $inline) {
        $changeset_ids[] = $inline->getComment()->getChangesetID();
      }

      $changesets = id(new DifferentialChangeset())->loadAllWhere(
        'id IN (%Ld)',
        $changeset_ids);

      foreach ($inlines as $inline) {
        $comment = $inline->getComment();
        $changeset = idx($changesets, $comment->getChangesetID());
        if (!$changeset) {
          continue;
        }

        $filename = $changeset->getDisplayFilename();
        $linenumber = $comment->getLineNumber();
        $inline_text = $engine->markupText($comment->getContent());
        $inline_text = rtrim($inline_text);

        $body .= "{$filename}:{$linenumber} {$inline_text}\n";
      }
    }

    return $body;
  }

  public function newWarningForTransactions($object, array $xactions) {
    $warning = new PhabricatorTransactionWarning();

    switch ($this->getTransactionType()) {
      case self::TYPE_INLINE:
        $warning->setTitleText(pht('Warning: Editing Inlines'));
        $warning->setContinueActionText(pht('Save Inlines and Continue'));

        $count = phutil_count($xactions);

        $body = array();
        $body[] = pht(
          'You are currently editing %s inline comment(s) on this '.
          'revision.',
          $count);
        $body[] = pht(
          'These %s inline comment(s) will be saved and published.',
          $count);

        $warning->setWarningParagraphs($body);
        break;
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
        $warning->setTitleText(pht('Warning: Draft Revision'));
        $warning->setContinueActionText(pht('Tell No One'));

        $body = array();

        $body[] = pht(
          'This is a draft revision that will not publish any '.
          'notifications until the author requests review.');

        $body[] = pht('Mentioned or subscribed users will not be notified.');

        $warning->setWarningParagraphs($body);
        break;
    }

    return $warning;
  }


}
