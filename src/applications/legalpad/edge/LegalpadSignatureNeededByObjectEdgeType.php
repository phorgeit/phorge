<?php

final class LegalpadSignatureNeededByObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 50;

  public function getInverseEdgeConstant() {
    return LegalpadObjectNeedsSignatureEdgeType::EDGECONST;
  }

}
