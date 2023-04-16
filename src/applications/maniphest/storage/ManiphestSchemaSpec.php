<?php

final class ManiphestSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new ManiphestTask());
  }

}
