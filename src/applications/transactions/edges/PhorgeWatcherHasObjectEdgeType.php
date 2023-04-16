<?php

final class PhorgeWatcherHasObjectEdgeType extends PhorgeEdgeType {

  const EDGECONST = 48;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasWatcherEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
