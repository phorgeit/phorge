<?php

final class PhorgeDashboardPanelUsedByObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 72;

  public function getInverseEdgeConstant() {
    return PhorgeObjectUsesDashboardPanelEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
