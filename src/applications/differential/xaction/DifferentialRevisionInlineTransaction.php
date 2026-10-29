<?php

final class DifferentialRevisionInlineTransaction
  extends DifferentialRevisionTransactionType {

  const TRANSACTIONTYPE = 'differential:inline';

  public function getActionName() {
    return pht('Commented On');
  }

  public function getTitle() {
    return pht(
      '%s added inline comments.',
      $this->renderAuthor());
  }

  public function getTitleForFeed() {
    return pht(
      '%s added inline comments to %s.',
      $this->renderAuthor(),
      $this->renderObject());
  }

  public function getIcon() {
    return 'fa-comment';
  }

  public function getTransactionTypeForConduit($xaction) {
    return 'inline';
  }

  public function getTransactionHasEffect($object, $old, $new) {
    return true;
  }

  public function loadTransactionTypeConduitData(array $xactions) {
    $viewer = $this->getViewer();

    $changeset_ids = array();
    $comment_ids = array();
    foreach ($xactions as $xaction) {
      $changeset_ids[] = $xaction->getComment()->getChangesetID();
      $comment_ids[] = $xaction->getComment()->getID();
    }

    $changesets = id(new DifferentialChangesetQuery())
      ->setViewer($viewer)
      ->withIDs($changeset_ids)
      ->execute();

    $changesets = mpull($changesets, null, 'getID');

    // Load the lines each inline is on, to tell whether a suggested edit
    // changes them.
    $inlines = id(new DifferentialDiffInlineCommentQuery())
      ->setViewer($viewer)
      ->withIDs($comment_ids)
      ->needInlineContext(true)
      ->execute();

    $inlines = mpull($inlines, null, 'getID');

    return array(
      'changesets' => $changesets,
      'inlines' => $inlines,
    );
  }

  public function generateOldValue($object) {
    return null;
  }

  public function applyInternalEffects($object, $value) {
    $this->purgeCommentDrafts();
  }

  public function applyExternalEffects($object, $value) {
    parent::updateCommentReplyTo();
  }


  public function getFieldValuesForConduit($object, $data) {
    $comment = $object->getComment();

    $changeset = $data['changesets'][$comment->getChangesetID()];
    $diff = $changeset->getDiff();

    $is_done = false;
    switch ($comment->getFixedState()) {
      case PhabricatorInlineComment::STATE_DONE:
      case PhabricatorInlineComment::STATE_UNDRAFT:
        $is_done = true;
        break;
    }

    // A suggested edit is stored apart from the comment text, which may be
    // empty when the inline is only a suggestion. The suggestion starts out
    // as the original lines, and the web UI shows nothing if it's left that
    // way, so return null then too.
    $suggestion = null;
    $state = $comment->newInlineCommentObject()->getContentState();
    if ($state->getContentHasSuggestion()) {
      $text = $state->getContentSuggestionText();

      $original = null;
      $inline = idx($data['inlines'], $comment->getID());
      if ($inline) {
        $original = $inline->newInlineCommentObject()
          ->getDefaultSuggestionText();
      }

      if (phutil_nonempty_string($text) && ($text !== $original)) {
        $suggestion = $text;
      }
    }

    return array(
      'diff' => array(
        'id' => (int)$diff->getID(),
        'phid' => $diff->getPHID(),
      ),
      'path' => $changeset->getDisplayFilename(),
      'line' => (int)$comment->getLineNumber(),
      'length' => (int)($comment->getLineLength() + 1),
      'replyToCommentPHID' => $comment->getReplyToCommentPHID(),
      'isDone' => $is_done,
      'suggestion' => $suggestion,
    );
  }

}
