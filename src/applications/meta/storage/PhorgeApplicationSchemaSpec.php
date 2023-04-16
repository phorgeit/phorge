<?php

final class PhorgeApplicationSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeApplicationsApplication());
  }

}
