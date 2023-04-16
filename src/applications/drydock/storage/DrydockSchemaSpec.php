<?php

final class DrydockSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new DrydockBlueprint());
  }

}
