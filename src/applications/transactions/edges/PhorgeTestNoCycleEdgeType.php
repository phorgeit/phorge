<?php

final class PhorgeTestNoCycleEdgeType extends PhorgeEdgeType {

  const EDGECONST = 9000;

  public function shouldPreventCycles() {
    return true;
  }

}
