<?php

/**
 * Meta-trait do expose some methods from PhabricatorCustomField to other
 * traits.
 * These are all implemented in PhabricatorCustomField.
 */
trait PhorgeCustomFieldMetaTrait {

  /**
   * @return PhabricatorUser
   */
  abstract public function getViewer();

}
