<?php

final class PhorgeOAuthServerSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeOAuthServerClient());
  }

}
