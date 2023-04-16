<?php

final class PhorgeSubscribedToObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 22;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasSubscriberEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
