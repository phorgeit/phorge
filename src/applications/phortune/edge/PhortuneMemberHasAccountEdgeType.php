<?php

final class PhortuneMemberHasAccountEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 28;

  public function getInverseEdgeConstant() {
    return PhortuneAccountHasMemberEdgeType::EDGECONST;
  }

}
