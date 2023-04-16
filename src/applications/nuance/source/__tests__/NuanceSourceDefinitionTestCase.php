<?php

final class NuanceSourceDefinitionTestCase extends PhorgeTestCase {

  public function testGetAllTypes() {
    NuanceSourceDefinition::getAllDefinitions();
    $this->assertTrue(true);
  }

}
