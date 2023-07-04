<?php

final class DiffusionServeControllerTestCase extends PhabricatorTestCase {
  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testHandleRequest() {
    $aphront_request = new AphrontRequest('example.com', '/');
    $diffusion_serve_controller = new DiffusionServeController();

    $diffusion_serve_controller->setRequest($aphront_request);
    $result = $diffusion_serve_controller->handleRequest($aphront_request);
    $this->assertTrue(true, 'handleRequest did not throw an error');
    $this->assertTrue($result instanceof PhabricatorVCSResponse,
      'handleRequest() returns PhabricatorVCSResponse object');
  }

}
