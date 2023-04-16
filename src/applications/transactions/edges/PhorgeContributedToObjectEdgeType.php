<?php

final class PhorgeContributedToObjectEdgeType extends PhorgeEdgeType {

  const EDGECONST = 34;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasContributorEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
