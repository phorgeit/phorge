<?php

final class PassphraseSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PassphraseCredential());
  }

}
