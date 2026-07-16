<?php

final class ManiphestTaskColumnTransactionType
  extends ManiphestTaskTransactionType {

  // The TRANSACTIONTYPE is `core:`, probably in preparation for someday having
  // other objects appear in Columns. For now, only tasks have ever supported
  // this transaction type, and all the text is hard-coded for Tasks.
  // If and when we allow other things in columns, revisit the class hierarchy.

  const TRANSACTIONTYPE = 'core:columns';

  public function getIcon() {
    return 'fa-columns';
  }

  public function shouldHide() {
    return !$this->getInterestingMoves($this->getNewValue());
  }

  public function getActionName() {
    return pht('Changed Project Column');
  }

  public function applyExternalEffects($object, $value) {
    foreach ($value as $move) {
      $this->applyBoardMove($object, $move);
    }
  }

  public function getTitle() {
    $author_phid = $this->getAuthorPHID();

    $new = $this->getNewValue();

    $moves = $this->getInterestingMoves($new);
    if (count($moves) == 1) {
      $move = head($moves);
      $from_columns = $move['fromColumnPHIDs'];
      $to_column = $move['columnPHID'];
      $board_phid = $move['boardPHID'];
      if (count($from_columns) == 1) {
        return pht(
          '%s moved this task from %s to %s on the %s board.',
          $this->renderHandle($author_phid),
          $this->renderHandle(head($from_columns)),
          $this->renderHandle($to_column),
          $this->renderHandle($board_phid));
      } else {
        return pht(
          '%s moved this task to %s on the %s board.',
          $this->renderHandle($author_phid),
          $this->renderHandle($to_column),
          $this->renderHandle($board_phid));
      }
    } else {
      $fragments = array();
      foreach ($moves as $move) {
        $to_column = $move['columnPHID'];
        $board_phid = $move['boardPHID'];
        $fragments[] = pht(
          '%s (%s)',
          $this->renderHandle($board_phid),
          $this->renderHandle($to_column));
      }

      return pht(
        '%s moved this task on %s board(s): %s.',
        $this->renderHandle($author_phid),
        phutil_count($moves),
        phutil_implode_html(', ', $fragments));
    }
  }

  public function getTitleForFeed() {
    $author_phid = $this->getAuthorPHID();
    $object_phid = $this->getObjectPHID();

    $new = $this->getNewValue();


    $moves = $this->getInterestingMoves($new);
    if (count($moves) == 1) {
      $move = head($moves);
      $from_columns = $move['fromColumnPHIDs'];
      $to_column = $move['columnPHID'];
      $board_phid = $move['boardPHID'];
      if (count($from_columns) == 1) {
        return pht(
          '%s moved %s from %s to %s on the %s board.',
          $this->renderHandle($author_phid),
          $this->renderHandle($object_phid),
          $this->renderHandle(head($from_columns)),
          $this->renderHandle($to_column),
          $this->renderHandle($board_phid));
      } else {
        return pht(
          '%s moved %s to %s on the %s board.',
          $this->renderHandle($author_phid),
          $this->renderHandle($object_phid),
          $this->renderHandle($to_column),
          $this->renderHandle($board_phid));
      }
    } else {
      $fragments = array();
      foreach ($moves as $move) {
        $to_column = $move['columnPHID'];
        $board_phid = $move['boardPHID'];
        $fragments[] = pht(
          '%s (%s)',
          $this->renderHandle($board_phid),
          $this->renderHandle($to_column));
      }

      return pht(
        '%s moved %s on %s board(s): %s.',
        $this->renderHandle($author_phid),
        $this->renderHandle($object_phid),
        phutil_count($moves),
        phutil_implode_html(', ', $fragments));
    }
  }

  public function getNoEffectDescription() {
    return pht(
      'You have not moved this object to any columns it is not '.
      'already in.');
  }

  protected function getBodyForMail() {
    // TODO I can't find any example of any xtype implementing this.
    // See also comment in PhabricatorModularTransactionType about all
    // mail-methods being "aspirational".

    return false;
  }

  private function getInterestingMoves(array $moves) {
    // Remove moves which only shift the position of a task within a column.
    foreach ($moves as $key => $move) {
      $from_phids = array_fuse($move['fromColumnPHIDs']);
      if (isset($from_phids[$move['columnPHID']])) {
        unset($moves[$key]);
      }
    }

    return $moves;
  }

  private function applyBoardMove($object, array $move) {
    $board_phid = $move['boardPHID'];
    $column_phid = $move['columnPHID'];

    $before_phids = $move['beforePHIDs'];
    $after_phids = $move['afterPHIDs'];

    $object_phid = $object->getPHID();

    // We're doing layout with the omnipotent viewer to make sure we don't
    // remove positions in columns that exist, but which the actual actor
    // can't see.
    $omnipotent_viewer = PhabricatorUser::getOmnipotentUser();

    $select_phids = array($board_phid);

    $descendants = id(new PhabricatorProjectQuery())
      ->setViewer($omnipotent_viewer)
      ->withAncestorProjectPHIDs($select_phids)
      ->execute();
    foreach ($descendants as $descendant) {
      $select_phids[] = $descendant->getPHID();
    }

    $board_tasks = id(new ManiphestTaskQuery())
      ->setViewer($omnipotent_viewer)
      ->withEdgeLogicPHIDs(
        PhabricatorProjectObjectHasProjectEdgeType::EDGECONST,
        PhabricatorQueryConstraint::OPERATOR_ANCESTOR,
        array($select_phids))
      ->execute();

    $board_tasks = mpull($board_tasks, null, 'getPHID');
    $board_tasks[$object_phid] = $object;

    // Make sure tasks are sorted by ID, so we lay out new positions in
    // a consistent way.
    $board_tasks = msort($board_tasks, 'getID');

    $object_phids = array_keys($board_tasks);

    $engine = id(new PhabricatorBoardLayoutEngine())
      ->setViewer($omnipotent_viewer)
      ->setBoardPHIDs(array($board_phid))
      ->setObjectPHIDs($object_phids)
      ->executeLayout();

    // TODO: This logic needs to be revised when we legitimately support
    // multiple column positions.
    $columns = $engine->getObjectColumns($board_phid, $object_phid);
    foreach ($columns as $column) {
      $engine->queueRemovePosition(
        $board_phid,
        $column->getPHID(),
        $object_phid);
    }

    $engine->queueAddPosition(
      $board_phid,
      $column_phid,
      $object_phid,
      $after_phids,
      $before_phids);

    $engine->applyPositionUpdates();
  }

  public function generateOldValue($object) {
    return null;
  }

  public function getTransactionHasEffect($object, $old, $new) {
    return (bool)$new;
  }

}
