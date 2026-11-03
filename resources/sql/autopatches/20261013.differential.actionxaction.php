<?php

$query = id(new DifferentialTransactionQuery())
  ->setViewer(PhabricatorUser::getOmnipotentUser())
  ->withTransactionTypes(array('differential:action'));

$iterator = id(new PhabricatorQueryIterator($query))
  ->setPageSize(1000);

foreach ($iterator as $xaction) {

  $action = $xaction->getNewValue();

  $backup_metadata = array(
    'oldValue' => $xaction->getOldValue(),
    'metadata' => $xaction->getMetadata(),
    'action' => $action,
  );

  $backup_metadata = array_filter($backup_metadata);

  $new_type = null;
  $new_new_value = true; // the value for most actions
  $new_old_value = null;
  $new_metadata = array();

  switch ($action) {
    case 'commit':
      $new_type = DifferentialRevisionCloseTransaction::TRANSACTIONTYPE;
      $new_old_value = false;
      $new_metadata = array_select_keys(
        $xaction->getMetadata(),
        array('isCommitClose', 'commitPHID'));
      break;

    case 'accept':
      $new_type = DifferentialRevisionAcceptTransaction::TRANSACTIONTYPE;
      break;

    case 'reject':
      $new_type = DifferentialRevisionRejectTransaction::TRANSACTIONTYPE;
      break;
    case 'rethink':
      $new_type = DifferentialRevisionPlanChangesTransaction::TRANSACTIONTYPE;
      break;
    case 'abandon':
      $new_type = DifferentialRevisionAbandonTransaction::TRANSACTIONTYPE;
      break;
    case 'request_review':
      $new_type = DifferentialRevisionRequestReviewTransaction::TRANSACTIONTYPE;
      break;
    case 'reclaim':
      $new_type = DifferentialRevisionReclaimTransaction::TRANSACTIONTYPE;
      break;
    case 'resign':
      $new_type = DifferentialRevisionResignTransaction::TRANSACTIONTYPE;
      break;

    case 'claim':
      $new_type = DifferentialRevisionCommandeerTransaction::TRANSACTIONTYPE;
      break;
    case 'reopen':
      $new_type = DifferentialRevisionReopenTransaction::TRANSACTIONTYPE;
      break;

    case 'create':
      $new_type = PhabricatorCoreCreateTransaction::TRANSACTIONTYPE;
      $new_new_value = null;
      break;

    case 'summarize':
      $new_type = DifferentialRevisionSummaryTransaction::TRANSACTIONTYPE;
      $new_old_value = '';
      $new_new_value = '';
      break;
    case 'testplan':
      $new_type = DifferentialRevisionTestPlanTransaction::TRANSACTIONTYPE;
      $new_old_value = '';
      $new_new_value = '';
      // I think the actual before-and-after content was not recorded;
      // rP32dd8af9e suggests the new content was kept as a Comment, so it would
      // still render as one.
    break;


    case 'none':
    case 'add_reviewers':
    case 'add_ccs':
    case 'update':
      echo pht(
        'This xaction should not exist - %s with action %s. Ignoring.'."\n",
        $xaction->getPHID(),
        $action);
      continue 2;

    default:
      echo pht(
        'Unknown old action: `%s`, xaction %s. Ignoring.'."\n",
        $action,
        $xaction->getPHID());
      continue 2;
  }

  $new_metadata['backupMetadata'] = $backup_metadata;

  echo pht(
    'Converting transaction %s with action `%s` to `%s`'."\n",
      $xaction->getPHID(),
      $action,
      $new_type);

  $xaction
    ->setTransactionType($new_type)
    ->setNewValue($new_new_value)
    ->setOldValue($new_old_value)
    ->setMetadata($new_metadata)
    ->save();
}

echo pht('Done.')."\n";
