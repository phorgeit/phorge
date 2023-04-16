<?php

final class PhorgeObjectHasAsanaSubtaskEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 80003;

  public function getInverseEdgeConstant() {
    return PhorgeAsanaSubtaskHasObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
