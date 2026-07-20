<?php

final class PhabricatorAuditActionConstants extends Phobject {

  const CONCERN   = 'concern';
  const ACCEPT    = 'accept';
  const RESIGN    = 'resign';
  const CLOSE     = 'close';

  const COMMENT   = 'comment';

  /** @deprecated see
   * PhorgeAuditCommitInlineCommentTransaction::TRANSACTIONTYPE */
  const INLINE = PhorgeAuditCommitInlineCommentTransaction::TRANSACTIONTYPE;

  /** @deprecated see PhorgeAuditCommitAddCCTransaction::TRANSACTIONTYPE */
  const ADD_CCS = PhorgeAuditCommitAddCCTransaction::TRANSACTIONTYPE;
  /** @deprecated see PhorgeAuditCommitAddAuditorTransaction::TRANSACTIONTYPE */
  const ADD_AUDITORS = PhorgeAuditCommitAddAuditorTransaction::TRANSACTIONTYPE;
  /** @deprecated see PhorgeAuditCommitActionTransaction::TRANSACTIONTYPE */
  const ACTION = PhorgeAuditCommitActionTransaction::TRANSACTIONTYPE;

}
