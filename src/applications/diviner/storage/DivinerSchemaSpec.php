<?php

final class DivinerSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new DivinerLiveBook());
  }

}
