<?php

final class PhorgeEdgeTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testCycleDetection() {

    // The editor should detect that this introduces a cycle and prevent the
    // edit.

    $user = new PhorgeUser();

    $obj1 = id(new HarbormasterObject())->save();
    $obj2 = id(new HarbormasterObject())->save();
    $phid1 = $obj1->getPHID();
    $phid2 = $obj2->getPHID();

    $editor = id(new PhorgeEdgeEditor())
      ->addEdge($phid1, PhorgeTestNoCycleEdgeType::EDGECONST , $phid2)
      ->addEdge($phid2, PhorgeTestNoCycleEdgeType::EDGECONST , $phid1);

    $caught = null;
    try {
      $editor->save();
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);


    // The first edit should go through (no cycle), bu the second one should
    // fail (it introduces a cycle).

    $editor = id(new PhorgeEdgeEditor())
      ->addEdge($phid1, PhorgeTestNoCycleEdgeType::EDGECONST , $phid2)
      ->save();

    $editor = id(new PhorgeEdgeEditor())
      ->addEdge($phid2, PhorgeTestNoCycleEdgeType::EDGECONST , $phid1);

    $caught = null;
    try {
      $editor->save();
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }


}
