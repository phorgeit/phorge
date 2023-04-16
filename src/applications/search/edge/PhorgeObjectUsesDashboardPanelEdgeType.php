<?php

final class PhorgeObjectUsesDashboardPanelEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 71;

  public function getInverseEdgeConstant() {
    return PhorgeDashboardPanelUsedByObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
