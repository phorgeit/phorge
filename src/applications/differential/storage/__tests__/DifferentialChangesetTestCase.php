<?php

final class DifferentialChangesetTestCase extends PhabricatorTestCase {

  public function testPhp81() {
    $diff_change_set = new DifferentialChangeset();
    try {
      $old_state_vector = $diff_change_set->getOldStatePathVector();
      $this->assertTrue(true, 'getOldStatePathVector did not throw an error');
    } catch (Throwable $ex) {
      $this->assertTrue(false,
        'getOldStatePathVector threw an exception:'.$ex->getMessage());
    }
  }

}
