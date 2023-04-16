<?php

final class PhorgeObjectHasAsanaTaskEdgeType extends PhorgeEdgeType {

  const EDGECONST = 80001;

  public function getInverseEdgeConstant() {
    return PhorgeAsanaTaskHasObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
