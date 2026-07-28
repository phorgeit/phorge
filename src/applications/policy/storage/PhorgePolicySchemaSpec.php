<?php

final class PhorgePolicySchemaSpec
 extends PhabricatorConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeNamedPolicy());
  }

}
