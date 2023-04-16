<?php

$conn_w = id(new PhorgeAuditTransaction())->establishConnection('w');
$rows = new LiskRawMigrationIterator($conn_w, 'audit_comment');

$content_source = PhorgeContentSource::newForSource(
  PhorgeOldWorldContentSource::SOURCECONST)->serialize();

echo pht('Migrating Audit comment text to modern storage...')."\n";
foreach ($rows as $row) {
  $id = $row['id'];
  echo pht('Migrating Audit comment %d...', $id)."\n";
  if (!strlen($row['content'])) {
    echo pht('Comment has no text, continuing.')."\n";
    continue;
  }

  $xaction_phid = PhorgePHID::generateNewPHID(
    PhorgeApplicationTransactionTransactionPHIDType::TYPECONST,
    PhorgeRepositoryCommitPHIDType::TYPECONST);

  $comment_phid = PhorgePHID::generateNewPHID(
    PhorgePHIDConstants::PHID_TYPE_XCMT,
    PhorgeRepositoryCommitPHIDType::TYPECONST);

  queryfx(
    $conn_w,
    'INSERT IGNORE INTO %T
      (phid, transactionPHID, authorPHID, viewPolicy, editPolicy,
        commentVersion, content, contentSource, isDeleted,
        dateCreated, dateModified, commitPHID, pathID,
        legacyCommentID)
      VALUES (%s, %s, %s, %s, %s,
        %d, %s, %s, %d,
        %d, %d, %s, %nd,
        %d)',
    'audit_transaction_comment',

    // phid, transactionPHID, authorPHID, viewPolicy, editPolicy
    $comment_phid,
    $xaction_phid,
    $row['actorPHID'],
    'public',
    $row['actorPHID'],

    // commentVersion, content, contentSource, isDeleted
    1,
    $row['content'],
    $content_source,
    0,

    // dateCreated, dateModified, commitPHID, pathID, legacyCommentID
    $row['dateCreated'],
    $row['dateModified'],
    $row['targetPHID'],
    null,
    $row['id']);
}

echo pht('Done.')."\n";
