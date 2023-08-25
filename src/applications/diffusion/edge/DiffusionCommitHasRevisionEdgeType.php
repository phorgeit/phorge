<?php

final class DiffusionCommitHasRevisionEdgeType extends PhabricatorEdgeType {

  const EDGECONST = 32;

  public function getInverseEdgeConstant() {
    return DifferentialRevisionHasCommitEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

  public function getConduitKey() {
    return 'commit.revision';
  }

  public function getConduitName() {
    return pht('Commit Has Revision');
  }

  public function getConduitDescription() {
    return pht(
      'The source commit is associated with the destination revision.');
  }

  public function getTransactionAddString(
    $actor,
    $add_count,
    $add_edges) {

    return pht(
      '%s added %s revision(s): %s.',
      $actor,
      $add_count,
      $add_edges);
  }

  public function getTransactionRemoveString(
    $actor,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s removed %s revision(s): %s.',
      $actor,
      $rem_count,
      $rem_edges);
  }

  public function getTransactionEditString(
    $actor,
    $total_count,
    $add_count,
    $add_edges,
    $rem_count,
    $rem_edges) {

    return pht(
      '%s edited revision(s), added %s: %s; removed %s: %s.',
      $actor,
      $add_count,
      $add_edges,
      $rem_count,
      $rem_edges);
  }

}
