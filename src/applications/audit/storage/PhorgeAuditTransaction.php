<?php

final class PhorgeAuditTransaction
  extends PhorgeModularTransaction {

  const TYPE_COMMIT = 'audit:commit';

  const MAILTAG_ACTION_CONCERN = 'audit-action-concern';
  const MAILTAG_ACTION_ACCEPT  = 'audit-action-accept';
  const MAILTAG_ACTION_RESIGN  = 'audit-action-resign';
  const MAILTAG_ACTION_CLOSE   = 'audit-action-close';
  const MAILTAG_ADD_AUDITORS   = 'audit-add-auditors';
  const MAILTAG_ADD_CCS        = 'audit-add-ccs';
  const MAILTAG_COMMENT        = 'audit-comment';
  const MAILTAG_COMMIT         = 'audit-commit';
  const MAILTAG_PROJECTS       = 'audit-projects';
  const MAILTAG_OTHER          = 'audit-other';

  public function getApplicationName() {
    return 'audit';
  }

  public function getBaseTransactionClass() {
    return 'DiffusionCommitTransactionType';
  }

  public function getApplicationTransactionType() {
    return PhorgeRepositoryCommitPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeAuditTransactionComment();
  }

  public function getRemarkupBlocks() {
    $blocks = parent::getRemarkupBlocks();

    switch ($this->getTransactionType()) {
    case self::TYPE_COMMIT:
      $data = $this->getNewValue();
      $blocks[] = $data['description'];
      break;
    }

    return $blocks;
  }

  public function getActionStrength() {
    $type = $this->getTransactionType();

    switch ($type) {
      case self::TYPE_COMMIT:
        return 300;
    }

    return parent::getActionStrength();
  }

  public function getRequiredHandlePHIDs() {
    $phids = parent::getRequiredHandlePHIDs();

    $type = $this->getTransactionType();

    switch ($type) {
      case self::TYPE_COMMIT:
        $phids[] = $this->getObjectPHID();
        $data = $this->getNewValue();
        if ($data['authorPHID']) {
          $phids[] = $data['authorPHID'];
        }
        if ($data['committerPHID']) {
          $phids[] = $data['committerPHID'];
        }
        break;
      case PhorgeAuditActionConstants::ADD_CCS:
      case PhorgeAuditActionConstants::ADD_AUDITORS:
        $old = $this->getOldValue();
        $new = $this->getNewValue();

        if (!is_array($old)) {
          $old = array();
        }
        if (!is_array($new)) {
          $new = array();
        }

        foreach (array_keys($old + $new) as $phid) {
          $phids[] = $phid;
        }
        break;
    }

    return $phids;
  }

  public function getActionName() {

    switch ($this->getTransactionType()) {
      case PhorgeAuditActionConstants::ACTION:
        switch ($this->getNewValue()) {
          case PhorgeAuditActionConstants::CONCERN:
            return pht('Raised Concern');
          case PhorgeAuditActionConstants::ACCEPT:
            return pht('Accepted');
          case PhorgeAuditActionConstants::RESIGN:
            return pht('Resigned');
          case PhorgeAuditActionConstants::CLOSE:
            return pht('Closed');
        }
        break;
      case PhorgeAuditActionConstants::ADD_AUDITORS:
        return pht('Added Auditors');
      case self::TYPE_COMMIT:
        return pht('Committed');
    }

    return parent::getActionName();
  }

  public function getColor() {

    $type = $this->getTransactionType();

    switch ($type) {
      case PhorgeAuditActionConstants::ACTION:
        switch ($this->getNewValue()) {
          case PhorgeAuditActionConstants::CONCERN:
            return 'red';
          case PhorgeAuditActionConstants::ACCEPT:
            return 'green';
          case PhorgeAuditActionConstants::RESIGN:
            return 'black';
          case PhorgeAuditActionConstants::CLOSE:
            return 'indigo';
        }
    }

    return parent::getColor();
  }

  public function getIcon() {

    $type = $this->getTransactionType();

    switch ($type) {
      case PhorgeAuditActionConstants::ACTION:
        switch ($this->getNewValue()) {
          case PhorgeAuditActionConstants::CONCERN:
            return 'fa-exclamation-circle';
          case PhorgeAuditActionConstants::ACCEPT:
            return 'fa-check';
          case PhorgeAuditActionConstants::RESIGN:
            return 'fa-plane';
          case PhorgeAuditActionConstants::CLOSE:
            return 'fa-check';
        }
    }

    return parent::getIcon();
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $author_handle = $this->renderHandleLink($this->getAuthorPHID());

    $type = $this->getTransactionType();

    switch ($type) {
      case PhorgeAuditActionConstants::ADD_CCS:
      case PhorgeAuditActionConstants::ADD_AUDITORS:
        if (!is_array($old)) {
          $old = array();
        }
        if (!is_array($new)) {
          $new = array();
        }
        $add = array_keys(array_diff_key($new, $old));
        $rem = array_keys(array_diff_key($old, $new));
        break;
    }

    switch ($type) {
      case self::TYPE_COMMIT:
        $author = null;
        if ($new['authorPHID']) {
          $author = $this->renderHandleLink($new['authorPHID']);
        } else {
          $author = $new['authorName'];
        }

        $committer = null;
        if ($new['committerPHID']) {
          $committer = $this->renderHandleLink($new['committerPHID']);
        } else if ($new['committerName']) {
          $committer = $new['committerName'];
        }

        $commit = $this->renderHandleLink($this->getObjectPHID());

        if (!$committer) {
          $committer = $author;
          $author = null;
        }

        if ($author) {
          $title = pht(
            '%s committed %s (authored by %s).',
            $committer,
            $commit,
            $author);
        } else {
          $title = pht(
            '%s committed %s.',
            $committer,
            $commit);
        }
        return $title;

      case PhorgeAuditActionConstants::INLINE:
        return pht(
          '%s added inline comments.',
          $author_handle);

      case PhorgeAuditActionConstants::ADD_CCS:
        if ($add && $rem) {
          return pht(
            '%s edited subscribers; added: %s, removed: %s.',
            $author_handle,
            $this->renderHandleList($add),
            $this->renderHandleList($rem));
        } else if ($add) {
          return pht(
            '%s added subscribers: %s.',
            $author_handle,
            $this->renderHandleList($add));
        } else if ($rem) {
          return pht(
            '%s removed subscribers: %s.',
            $author_handle,
            $this->renderHandleList($rem));
        } else {
          return pht(
            '%s added subscribers...',
            $author_handle);
        }

      case PhorgeAuditActionConstants::ADD_AUDITORS:
        if ($add && $rem) {
          return pht(
            '%s edited auditors; added: %s, removed: %s.',
            $author_handle,
            $this->renderHandleList($add),
            $this->renderHandleList($rem));
        } else if ($add) {
          return pht(
            '%s added auditors: %s.',
            $author_handle,
            $this->renderHandleList($add));
        } else if ($rem) {
          return pht(
            '%s removed auditors: %s.',
            $author_handle,
            $this->renderHandleList($rem));
        } else {
          return pht(
            '%s added auditors...',
            $author_handle);
        }

      case PhorgeAuditActionConstants::ACTION:
        switch ($new) {
          case PhorgeAuditActionConstants::ACCEPT:
            return pht(
              '%s accepted this commit.',
              $author_handle);
          case PhorgeAuditActionConstants::CONCERN:
            return pht(
              '%s raised a concern with this commit.',
              $author_handle);
          case PhorgeAuditActionConstants::RESIGN:
            return pht(
              '%s resigned from this audit.',
              $author_handle);
          case PhorgeAuditActionConstants::CLOSE:
            return pht(
              '%s closed this audit.',
              $author_handle);
        }

    }

    return parent::getTitle();
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $author_handle = $this->renderHandleLink($this->getAuthorPHID());
    $object_handle = $this->renderHandleLink($this->getObjectPHID());

    $type = $this->getTransactionType();

    switch ($type) {
      case PhorgeAuditActionConstants::ADD_CCS:
      case PhorgeAuditActionConstants::ADD_AUDITORS:
        if (!is_array($old)) {
          $old = array();
        }
        if (!is_array($new)) {
          $new = array();
        }
        $add = array_keys(array_diff_key($new, $old));
        $rem = array_keys(array_diff_key($old, $new));
        break;
    }

    switch ($type) {
      case self::TYPE_COMMIT:
        $author = null;
        if ($new['authorPHID']) {
          $author = $this->renderHandleLink($new['authorPHID']);
        } else {
          $author = $new['authorName'];
        }

        $committer = null;
        if ($new['committerPHID']) {
          $committer = $this->renderHandleLink($new['committerPHID']);
        } else if ($new['committerName']) {
          $committer = $new['committerName'];
        }

        if (!$committer) {
          $committer = $author;
          $author = null;
        }

        if ($author) {
          $title = pht(
            '%s committed %s (authored by %s).',
            $committer,
            $object_handle,
            $author);
        } else {
          $title = pht(
            '%s committed %s.',
            $committer,
            $object_handle);
        }
        return $title;

      case PhorgeAuditActionConstants::INLINE:
        return pht(
          '%s added inline comments to %s.',
          $author_handle,
          $object_handle);

      case PhorgeAuditActionConstants::ADD_AUDITORS:
        if ($add && $rem) {
          return pht(
            '%s edited auditors for %s; added: %s, removed: %s.',
            $author_handle,
            $object_handle,
            $this->renderHandleList($add),
            $this->renderHandleList($rem));
        } else if ($add) {
          return pht(
            '%s added auditors to %s: %s.',
            $author_handle,
            $object_handle,
            $this->renderHandleList($add));
        } else if ($rem) {
          return pht(
            '%s removed auditors from %s: %s.',
            $author_handle,
            $object_handle,
            $this->renderHandleList($rem));
        } else {
          return pht(
            '%s added auditors to %s...',
            $author_handle,
            $object_handle);
        }

      case PhorgeAuditActionConstants::ACTION:
        switch ($new) {
          case PhorgeAuditActionConstants::ACCEPT:
            return pht(
              '%s accepted %s.',
              $author_handle,
              $object_handle);
          case PhorgeAuditActionConstants::CONCERN:
            return pht(
              '%s raised a concern with %s.',
              $author_handle,
              $object_handle);
          case PhorgeAuditActionConstants::RESIGN:
            return pht(
              '%s resigned from auditing %s.',
              $author_handle,
              $object_handle);
          case PhorgeAuditActionConstants::CLOSE:
            return pht(
              '%s closed the audit of %s.',
              $author_handle,
              $object_handle);
        }

    }

    return parent::getTitleForFeed();
  }

  public function getBodyForFeed(PhorgeFeedStory $story) {
    switch ($this->getTransactionType()) {
      case self::TYPE_COMMIT:
        $data = $this->getNewValue();
        return $story->renderSummary($data['summary']);
    }
    return parent::getBodyForFeed($story);
  }

  public function isInlineCommentTransaction() {
    switch ($this->getTransactionType()) {
      case PhorgeAuditActionConstants::INLINE:
        return true;
    }

    return parent::isInlineCommentTransaction();
  }

  public function getBodyForMail() {
    switch ($this->getTransactionType()) {
      case self::TYPE_COMMIT:
        $data = $this->getNewValue();
        return $data['description'];
    }

    return parent::getBodyForMail();
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case DiffusionCommitAcceptTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_ACCEPT;
        break;
      case DiffusionCommitConcernTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_CONCERN;
        break;
      case DiffusionCommitResignTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_RESIGN;
        break;
      case DiffusionCommitAuditorsTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ADD_AUDITORS;
        break;
      case PhorgeAuditActionConstants::ACTION:
        switch ($this->getNewValue()) {
          case PhorgeAuditActionConstants::CONCERN:
            $tags[] = self::MAILTAG_ACTION_CONCERN;
            break;
          case PhorgeAuditActionConstants::ACCEPT:
            $tags[] = self::MAILTAG_ACTION_ACCEPT;
            break;
          case PhorgeAuditActionConstants::RESIGN:
            $tags[] = self::MAILTAG_ACTION_RESIGN;
            break;
          case PhorgeAuditActionConstants::CLOSE:
            $tags[] = self::MAILTAG_ACTION_CLOSE;
            break;
        }
        break;
      case PhorgeAuditActionConstants::ADD_AUDITORS:
        $tags[] = self::MAILTAG_ADD_AUDITORS;
        break;
      case PhorgeAuditActionConstants::ADD_CCS:
        $tags[] = self::MAILTAG_ADD_CCS;
        break;
      case PhorgeAuditActionConstants::INLINE:
      case PhorgeTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case self::TYPE_COMMIT:
        $tags[] = self::MAILTAG_COMMIT;
        break;
      case PhorgeTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhorgeProjectObjectHasProjectEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_PROJECTS;
            break;
          case PhorgeObjectHasSubscriberEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_ADD_CCS;
            break;
          default:
            $tags[] = self::MAILTAG_OTHER;
            break;
        }
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

  public function shouldDisplayGroupWith(array $group) {
    // Make the "This commit now requires audit." state message stand alone.
    $type_state = DiffusionCommitStateTransaction::TRANSACTIONTYPE;

    if ($this->getTransactionType() == $type_state) {
      return false;
    }

    foreach ($group as $xaction) {
      if ($xaction->getTransactionType() == $type_state) {
        return false;
      }
    }

    return parent::shouldDisplayGroupWith($group);
  }

}
