<?php

interface PhabricatorInlineCommentInterface {

  public function newInlineCommentObject();

  /**
   * Mark this comment as having replies without saving any other changes.
   *
   * @return $this
   */
  public function markHasReplies();

}
