<?php

/**
 * Custom engine to run the celerity map tests on CSS or JS pages
 */
final class CelerityUnitTestEngine extends ArcanistUnitTestEngine {

  public function getEngineConfigurationName() {
    return 'celerity';
  }

  protected function supportsRunAllTests() {
    return true;
  }

  public function run() {
    if ($this->getRunAllTests() || $this->getPaths()) {
      $test_case = new PhabricatorCelerityTestCase();
      $test_case->willRunTestCases(array($test_case));
      $test_case->setWorkingCopy($this->getWorkingCopy());
      if ($this->renderer) {
        $test_case->setRenderer($this->renderer);
      }
      $result = $test_case->run();
      $test_case->didRunTestCases(array($test_case));
      return $result;
    }
    return array();
  }

 }
