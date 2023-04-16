<?php

final class ConpherenceSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new ConpherenceThread());
  }

}
