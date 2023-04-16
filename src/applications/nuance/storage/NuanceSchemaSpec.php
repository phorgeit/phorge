<?php

final class NuanceSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new NuanceItem());
  }

}
