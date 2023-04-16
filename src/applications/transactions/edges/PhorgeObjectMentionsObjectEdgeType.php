<?php

final class PhorgeObjectMentionsObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 52;

  public function getInverseEdgeConstant() {
    return PhorgeObjectMentionedByObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
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
