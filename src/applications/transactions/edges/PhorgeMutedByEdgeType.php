<?php

final class PhorgeMutedByEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 68;

  public function getInverseEdgeConstant() {
    return PhorgeMutedEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
