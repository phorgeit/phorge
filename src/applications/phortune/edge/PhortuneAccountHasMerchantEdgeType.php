<?php

final class PhortuneAccountHasMerchantEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 73;

  public function getInverseEdgeConstant() {
    return PhortuneMerchantHasAccountEdgeType::EDGECONST;
  }
}
