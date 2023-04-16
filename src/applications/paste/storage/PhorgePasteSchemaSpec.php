<?php

final class PhorgePasteSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgePaste());
  }

}
