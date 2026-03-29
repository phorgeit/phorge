<?php

final class PhabricatorPolicies extends PhabricatorPolicyConstants {

  const POLICY_PUBLIC   = 'public';
  const POLICY_USER     = 'users';
  const POLICY_ADMIN    = 'admin';
  const POLICY_NOONE    = 'no-one';

  /**
   * Returns the most public policy this install's configuration permits.
   * This is either "public" (if available) or "all users" (if not).
   *
   * @return string Most open working policy constant.
   */
  public static function getMostOpenPolicy() {
    if (PhabricatorEnv::getEnvConfig('policy.allow-public')) {
      return self::POLICY_PUBLIC;
    } else {
      return self::POLICY_USER;
    }
  }

  /**
   * Throw an exception if no condition in a getPolicy() implementation in a
   * subclass matched.
   *
   * @param PhabricatorPolicyCapability $capability Unmatched capability
   * @return never
   */
  public static function getFallbackPolicy($capability) {
    throw id(new PhabricatorPolicyException())
      ->setTitle(pht('Access Denied'))
      ->setRejection(pht('No such capability exists. This is a logic error '.
        'which should be reported as a bug.'))
      ->setCapability($capability);
  }

}
