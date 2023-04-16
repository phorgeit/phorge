<?php

final class PhorgeJiraIssueHasObjectEdgeType extends PhorgeEdgeType {

  const EDGECONST = 80004;

  public function getInverseEdgeConstant() {
    return PhorgeObjectHasJiraIssueEdgeType::EDGECONST;
  }

  public function shouldWriteInverseTransactions() {
    return true;
  }

}
