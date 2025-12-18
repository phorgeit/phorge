<?php

/**
 * An object can implement this interface to allow returning all users involved
 * in the object (e.g. authors, subscribers, reviewers, etc) and pass those
 * users in @{class:PhabricatorRemarkupControl} to the AutocompleteMap of the
 * Typeahead datasource.
 */
interface PhabricatorInvolveeInterface {


  /**
   * Get the PHIDs of all user accounts involved in a PhabricatorLiskDAO object
   *
   * @return array<string> User PHIDs
   */
  public function getInvolvedUsers();


}
