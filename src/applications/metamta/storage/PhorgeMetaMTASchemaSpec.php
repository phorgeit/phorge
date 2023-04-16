<?php

final class PhorgeMetaMTASchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(
      new PhorgeMetaMTAMail());
  }

}
