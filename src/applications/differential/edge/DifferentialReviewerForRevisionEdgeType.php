<?php

final class DifferentialReviewerForRevisionEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 36;

  public function getInverseEdgeConstant() {
    return DifferentialRevisionHasReviewerEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
