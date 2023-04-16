<?php

interface PhorgeAuthPasswordHashInterface {

  public function newPasswordDigest(
    PhutilOpaqueEnvelope $envelope,
    PhorgeAuthPassword $password);

  /**
   * Return a list of strings which passwords associated with this object may
   * not be similar to.
   *
   * This method allows you to prevent users from selecting their username
   * as their password or picking other passwords which are trivially similar
   * to an account or object identifier.
   *
   * @param PhorgeUser The user selecting the password.
   * @param PhorgeAuthPasswordEngine The password engine updating a
   *  password.
   * @return list<string> Blocklist of nonsecret identifiers which the password
   *  should not be similar to.
   */
  public function newPasswordBlocklist(
    PhorgeUser $viewer,
    PhorgeAuthPasswordEngine $engine);

}
