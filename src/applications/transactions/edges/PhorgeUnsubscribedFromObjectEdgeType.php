<?php

final class PhorgeUnsubscribedFromObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 24;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasUnsubscriberEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
