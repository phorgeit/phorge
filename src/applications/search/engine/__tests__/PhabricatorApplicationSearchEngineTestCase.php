<?php

final class PhabricatorApplicationSearchEngineTestCase
  extends PhabricatorTestCase {

  public function testGetAllEngines() {
    PhabricatorApplicationSearchEngine::getAllEngines();
    $this->assertTrue(true);
  }

  public function testPageSizeClampedToMaximum() {
    $engine = new PhabricatorPasteSearchEngine();

    $saved = $this->newSavedQueryWithLimit(1001);
    $this->assertEqual(1000, $engine->getPageSize($saved));
    $this->assertEqual(1001, $engine->getRequestedPageSize($saved));
  }

  public function testPageSizeBelowMaximumIsUnchanged() {
    $engine = new PhabricatorPasteSearchEngine();

    $saved = $this->newSavedQueryWithLimit(10);
    $this->assertEqual(10, $engine->getPageSize($saved));
    $this->assertEqual(10, $engine->getRequestedPageSize($saved));
  }

  public function testPageSizeDefaultsWhenUnset() {
    $engine = new PhabricatorPasteSearchEngine();

    $saved = new PhabricatorSavedQuery();
    $this->assertEqual(100, $engine->getPageSize($saved));
    $this->assertEqual(100, $engine->getRequestedPageSize($saved));
  }

  private function newSavedQueryWithLimit($limit) {
    $saved = new PhabricatorSavedQuery();
    $saved->setParameter('limit', $limit);
    return $saved;
  }

}
