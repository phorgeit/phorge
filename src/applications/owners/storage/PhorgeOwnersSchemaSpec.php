<?php

final class PhorgeOwnersSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeOwnersPackage());
  }

}
