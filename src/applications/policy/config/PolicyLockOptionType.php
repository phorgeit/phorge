<?php

final class PolicyLockOptionType
  extends PhorgeConfigJSONOptionType {

  public function validateOption(PhorgeConfigOption $option, $value) {
    $capabilities = id(new PhutilClassMapQuery())
      ->setAncestorClass('PhorgePolicyCapability')
      ->setUniqueMethod('getCapabilityKey')
      ->execute();

    $policy_phids = array();
    foreach ($value as $capability_key => $policy) {
      $capability = idx($capabilities, $capability_key);
      if (!$capability) {
        throw new Exception(
          pht(
            'Capability "%s" does not exist.',
            $capability_key));
      }
      if (phid_get_type($policy) !=
          PhorgePHIDConstants::PHID_TYPE_UNKNOWN) {
        $policy_phids[$policy] = $policy;
      } else {
        try {
          $policy_object = PhorgePolicyQuery::getGlobalPolicy($policy);
        // this exception is not helpful here as its about global policy;
        // throw a better exception
        } catch (Exception $ex) {
          throw new Exception(
            pht(
              'Capability "%s" has invalid policy "%s".',
              $capability_key,
              $policy));
        }
      }

      if ($policy == PhorgePolicies::POLICY_PUBLIC) {
        if (!$capability->shouldAllowPublicPolicySetting()) {
          throw new Exception(
            pht(
              'Capability "%s" does not support public policy.',
              $capability_key));
        }
      }
    }

    if ($policy_phids) {
      $handles = id(new PhorgeHandleQuery())
        ->setViewer(PhorgeUser::getOmnipotentUser())
        ->withPhids($policy_phids)
        ->execute();
      $handles = mpull($handles, null, 'getPHID');
      foreach ($value as $capability_key => $policy) {
        $handle = $handles[$policy];
        if (!$handle->isComplete()) {
          throw new Exception(
            pht(
              'Capability "%s" has invalid policy "%s"; "%s" does not exist.',
              $capability_key,
              $policy,
              $policy));
        }
      }
    }
  }

}
