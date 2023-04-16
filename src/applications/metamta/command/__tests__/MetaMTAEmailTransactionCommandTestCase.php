<?php

final class MetaMTAEmailTransactionCommandTestCase extends PhorgeTestCase {

  public function testGetAllTypes() {
    MetaMTAEmailTransactionCommand::getAllCommands();
    $this->assertTrue(true);
  }

}
