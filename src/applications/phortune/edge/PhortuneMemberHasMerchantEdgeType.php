<?php

final class PhortuneMemberHasMerchantEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 54;

  public function getInverseEdgeConstant() {
    return PhortuneMerchantHasMemberEdgeType::EDGECONST;
  }

}
