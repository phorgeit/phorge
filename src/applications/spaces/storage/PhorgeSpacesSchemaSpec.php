<?php

final class PhorgeSpacesSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeSpacesNamespace());
  }

}
