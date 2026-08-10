<?php

final class PhabricatorObjectMentionsObjectEdgeType
  extends PhabricatorEdgeType {

  const EDGECONST = 52;

  public function getInverseEdgeConstant() {
    return PhabricatorObjectMentionedByObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

  public function shouldHideInApplicationTransactions($xaction) {
    $record = PhabricatorEdgeChangeRecord::newFromTransaction($xaction);
    if ($record->getRemovedPHIDs()) {
      return false;
    }
    return true;
  }

  public function getTransactionRemoveString(
    $actor,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s removed mention of %s.',
      $actor,
      $rem_edges);
  }

  public function getFeedRemoveString(
    $actor,
    $object,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s removed mention of %s from %s.',
      $actor,
      $rem_edges,
      $object);
  }

  public function getConduitKey() {
    return 'mention';
  }

  public function getConduitName() {
    return pht('Mention');
  }

  public function getConduitDescription() {
    return pht(
      'The source object has a comment which mentions the destination object.');
  }

}
