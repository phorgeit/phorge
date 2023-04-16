<?php

final class LegalpadSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new LegalpadDocument());
  }

}
