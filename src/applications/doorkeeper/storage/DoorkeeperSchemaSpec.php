<?php

final class DoorkeeperSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new DoorkeeperExternalObject());
  }

}
