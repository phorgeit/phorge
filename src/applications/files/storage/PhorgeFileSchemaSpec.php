<?php

final class PhorgeFileSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeFile());
  }

}
