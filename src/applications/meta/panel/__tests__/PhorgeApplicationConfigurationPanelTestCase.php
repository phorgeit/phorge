<?php

final class PhorgeApplicationConfigurationPanelTestCase
  extends PhorgeTestCase {

  public function testLoadAllPanels() {
    PhorgeApplicationConfigurationPanel::loadAllPanels();
    $this->assertTrue(true);
  }

}
