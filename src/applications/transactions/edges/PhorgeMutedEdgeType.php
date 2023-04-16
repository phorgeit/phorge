<?php

final class PhorgeMutedEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 67;

  public function getInverseEdgeConstant() {
    return PhorgeMutedByEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
