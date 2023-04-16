<?php

final class PhorgeObjectHasJiraIssueEdgeType extends PhorgeEdgeType {

  const EDGECONST = 80005;

  public function getInverseEdgeConstant() {
    return PhorgeJiraIssueHasObjectEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
