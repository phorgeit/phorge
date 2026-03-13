<?php

/**
 * Meta-trait do define some texts that transaction-traits all need.
 * Would often be implemented implicitly in the abstract TransactionType class.
 */
trait PhorgeTransactionObjectNameTrait {

  /**
   * The type's name, singular, lower-case (for inclusion mid-sentence).
   */
  abstract protected function renderObjectType();

  /**
   * The type's name, singular, for TBD
   */
  // protected function renderObjectTypeTitleCase() {
    // This one should probably be overwritten anyway in each implementation
    // for localization.
    // return phutil_utf8_ucwords($this->renderObjectType());
  // }

}
