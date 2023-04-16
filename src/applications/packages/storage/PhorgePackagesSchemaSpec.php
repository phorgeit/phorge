<?php

final class PhorgePackagesSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgePackagesPublisher());
  }

}
