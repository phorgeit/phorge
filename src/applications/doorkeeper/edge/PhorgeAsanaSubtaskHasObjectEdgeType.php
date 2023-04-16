<?php

final class PhorgeAsanaSubtaskHasObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 80002;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasAsanaSubtaskEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
