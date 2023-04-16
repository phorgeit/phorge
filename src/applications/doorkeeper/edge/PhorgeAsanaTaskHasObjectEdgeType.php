<?php

final class PhorgeAsanaTaskHasObjectEdgeType extends PhorgeEdgeType {

  const EDGECONST = 80000;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasAsanaTaskEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
