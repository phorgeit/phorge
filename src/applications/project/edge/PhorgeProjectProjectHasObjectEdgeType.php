<?php

final class PhorgeProjectProjectHasObjectEdgeType
  extends PhorgeEdgeType {

  const EDGECONST = 42;

  public function getInverseEdgeConstant() {
    return PhorgeProjectObjectHasProjectEdgeType::EDGECONST;
  }

}
