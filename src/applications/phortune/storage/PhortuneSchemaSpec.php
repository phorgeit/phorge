<?php

final class PhortuneSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhortuneAccount());
  }

}
