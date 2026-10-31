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
    foreach ($xactions as $xaction) {
      $changeset_ids[] = $xaction->getComment()->getChangesetID();
    }

    $changesets = id(new DifferentialChangesetQuery())
      ->setViewer($viewer)
      ->withIDs($changeset_ids)
      ->execute();

    $changesets = mpull($changesets, null, 'getID');

    return $changesets;
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

    $changeset = $data[$comment->getChangesetID()];
    $diff = $changeset->getDiff();

    $is_done = false;
    switch ($comment->getFixedState()) {
      case PhabricatorInlineComment::STATE_DONE:
      case PhabricatorInlineComment::STATE_UNDRAFT:
        $is_done = true;
        break;
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
    );
  }

}
