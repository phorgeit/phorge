<?php

final class AlmanacSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new AlmanacService());
  }

}
