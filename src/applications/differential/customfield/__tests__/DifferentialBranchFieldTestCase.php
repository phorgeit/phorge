<?php

final class DifferentialBranchFieldTestCase extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  private function getTestDiff() {
  $parser = new ArcanistDiffParser();
  $raw_diff = <<<EODIFF
diff --git a/src b/src
index 123457..bb216b1 100644
--- a/src
+++ b/src
@@ -1,5 +1,5 @@
 Line a
-Line b
+Line 2
 Line c
 Line d
 Line e
EODIFF;

  return DifferentialDiff::newFromRawChanges(
    PhabricatorUser::getOmnipotentUser(),
    $parser->parseDiff($raw_diff));
  }

  public function testRenderDiffPropertyViewValue() {
    $test_object = new DifferentialBranchField();
    $diff = $this->getTestDiff();
    $diff->setBranch('test');
    $this->assertEqual('test',
      $test_object->renderDiffPropertyViewValue($diff));
  }
}
