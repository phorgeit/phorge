<?php

final class PhortuneMerchantHasAccountEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 74;

  public function getInverseEdgeConstant() {
    return PhortuneAccountHasMerchantEdgeType::EDGECONST;
  }

}
