<?php

final class PhorgePhurlSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgePhurlURL());
  }

}
