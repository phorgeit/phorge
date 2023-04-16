<?php

final class PholioSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PholioMock());
  }

}
