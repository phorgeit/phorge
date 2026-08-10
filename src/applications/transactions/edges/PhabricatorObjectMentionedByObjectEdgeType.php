<?php

final class PhabricatorObjectMentionedByObjectEdgeType
  extends PhabricatorEdgeType {

  const EDGECONST = 51;

  public function getInverseEdgeConstant() {
    return PhabricatorObjectMentionsObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

  public function getTransactionAddString(
    $actor,
    $add_count,
    $add_edges) {

    return pht(
      '%s mentioned this in %s.',
      $actor,
      $add_edges);
  }

  public function getTransactionRemoveString(
    $actor,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s removed mention from %s.',
      $actor,
      $rem_edges);
  }

  public function getFeedRemoveString(
    $actor,
    $object,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s removed mention from %s of %s.',
      $actor,
      $rem_edges,
      $object);
  }

  public function getConduitKey() {
    return 'mentioned-in';
  }

  public function getConduitName() {
    return pht('Mention In');
  }

  public function getConduitDescription() {
    return pht(
      'The source object is mentioned in a comment on the destination object.');
  }

}
