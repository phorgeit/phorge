<?php

final class PhorgeMetaMTAMailBodyTestCase extends PhorgeTestCase {

  public function testBodyRender() {
    $expect = <<<EOTEXT
salmon

HEADER
  bass
  trout

EOTEXT;

    $this->assertEmail($expect);
  }

  private function assertEmail($expect) {
    $body = new PhorgeMetaMTAMailBody();
    $body->addRawSection('salmon');
    $body->addTextSection('HEADER', "bass\ntrout\n");

    $this->assertEqual($expect, $body->render());
  }

}
