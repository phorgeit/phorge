<?php

final class PhorgePHIDTypeTestCase extends PhutilTestCase {

  public function testGetAllTypes() {
    PhorgePHIDType::getAllTypes();
    $this->assertTrue(true);
  }

  public function testGetPHIDTypeApplicationClass() {
    $types = PhorgePHIDType::getAllTypes();

    foreach ($types as $type) {
      $application_class = $type->getPHIDTypeApplicationClass();

      if ($application_class !== null) {
        $this->assertTrue(class_exists($application_class));
      }
    }
  }

}
