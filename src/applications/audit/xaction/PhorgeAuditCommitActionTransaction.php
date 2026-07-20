<?php

/**
 * @deprecated this transaction can no longer be created after b5722a9963.
 * Only kept for rendering transactions that were created before that.
 */
final class PhorgeAuditCommitActionTransaction
  extends PhorgeAuditCommitTransactionType {

  const TRANSACTIONTYPE = 'audit:action';

  public function getActionName() {
    switch ($this->getNewValue()) {
      case PhabricatorAuditActionConstants::CONCERN:
        return pht('Raised Concern');
      case PhabricatorAuditActionConstants::ACCEPT:
        return pht('Accepted');
      case PhabricatorAuditActionConstants::RESIGN:
        return pht('Resigned');
      case PhabricatorAuditActionConstants::CLOSE:
        return pht('Closed');
    }
  }

  public function getColor() {
    switch ($this->getNewValue()) {
      case PhabricatorAuditActionConstants::CONCERN:
        return 'red';
      case PhabricatorAuditActionConstants::ACCEPT:
        return 'green';
      case PhabricatorAuditActionConstants::RESIGN:
        return 'black';
      case PhabricatorAuditActionConstants::CLOSE:
        return 'indigo';
    }
  }

  public function getIcon() {
    switch ($this->getNewValue()) {
      case PhabricatorAuditActionConstants::CONCERN:
        return 'fa-exclamation-circle';
      case PhabricatorAuditActionConstants::ACCEPT:
      case PhabricatorAuditActionConstants::CLOSE:
        return 'fa-check';
      case PhabricatorAuditActionConstants::RESIGN:
        return 'fa-plane';
    }
  }

  public function getTitle() {

    $author_handle = $this->renderAuthor();
    switch ($this->getNewValue()) {
      case PhabricatorAuditActionConstants::ACCEPT:
        return pht(
          '%s accepted this commit.',
          $author_handle);
      case PhabricatorAuditActionConstants::CONCERN:
        return pht(
          '%s raised a concern with this commit.',
          $author_handle);
      case PhabricatorAuditActionConstants::RESIGN:
        return pht(
          '%s resigned from this audit.',
          $author_handle);
      case PhabricatorAuditActionConstants::CLOSE:
        return pht(
          '%s closed this audit.',
          $author_handle);
    }
  }

  public function getTitleForFeed() {

    $author_handle = $this->renderAuthor();
    $object_handle = $this->renderObject();

    switch ($this->getNewValue()) {
      case PhabricatorAuditActionConstants::ACCEPT:
        return pht(
          '%s accepted %s.',
          $author_handle,
          $object_handle);
      case PhabricatorAuditActionConstants::CONCERN:
        return pht(
          '%s raised a concern with %s.',
          $author_handle,
          $object_handle);
      case PhabricatorAuditActionConstants::RESIGN:
        return pht(
          '%s resigned from auditing %s.',
          $author_handle,
          $object_handle);
      case PhabricatorAuditActionConstants::CLOSE:
        return pht(
          '%s closed the audit of %s.',
          $author_handle,
          $object_handle);
    }
  }

}
