<?php

/**
 * @extends PhabricatorCursorPagedPolicyAwareQuery<PhabricatorPolicy>
 */
final class PhabricatorPolicyQuery
  extends PhabricatorCursorPagedPolicyAwareQuery {

  private $object;
  private $phids;

  private $needPolicyDetails;

  const OBJECT_POLICY_PREFIX = 'obj.';

  public function setObject(PhabricatorPolicyInterface $object) {
    // setObject() is used (probably) only for populating a Policy Selection UI
    $this->object = $object;
    return $this;
  }

  public function withPHIDs(array $phids) {
    $this->phids = $phids;
    return $this;
  }

  /**
   * If we're only interested in policy checks, we don't need all the details
   * that the user might not be able to see (e.g. the name of a project policy).
   * If we're showing something to the user, load more data (including policy
   * checks on each new data).
   */
  public function needPolicyDetails($need_details) {
    $this->needPolicyDetails = $need_details;
    return $this;
  }

  public static function loadPolicies(
    PhabricatorUser $viewer,
    PhabricatorPolicyInterface $object) {

    $results = array();

    $map = array();
    foreach ($object->getCapabilities() as $capability) {
      $map[$capability] = $object->getPolicy($capability);
    }

    $policies = id(new self())
      ->setViewer($viewer)
      ->withPHIDs($map)
      ->needPolicyDetails(true)
      ->execute();

    foreach ($map as $capability => $phid) {
      $results[$capability] = $policies[$phid];
    }

    return $results;
  }

  public static function renderPolicyDescriptions(
    PhabricatorUser $viewer,
    PhabricatorPolicyInterface $object) {

    $policies = self::loadPolicies($viewer, $object);

    foreach ($policies as $capability => $policy) {
      $policies[$capability] = $policy->newRef($viewer)
        ->newCapabilityLink($object, $capability);
    }

    return $policies;
  }

  protected function loadPage() {
    if ($this->object && $this->phids) {
      throw new Exception(
        pht(
          'You can not issue a policy query with both %s and %s.',
          'setObject()',
          'setPHIDs()'));
    } else if ($this->object) {
      $phids = $this->loadObjectPolicyPHIDs();
      // When we're provided with an object, we almost always
      // need to display something.
      // OTOH, when provided with an object, we'll need at most 2-3 policies
      // (only the ones that are directly on the object).
      $this->needPolicyDetails(true);
    } else {
      $phids = $this->phids;
    }

    $phids = array_fuse($phids);
    // I don't want to return results that weren't requested;
    // This clones the list:
    $requested = $phids;

    // Map of phid -> policy object. Also used as cache for the query.
    $results = array();

    // First, load global policies.
    foreach (self::getGlobalPolicies() as $phid => $policy) {
      $results[$phid] = $policy;
      unset($phids[$phid]);
    }

    // Now, load object policies.
    foreach (self::getObjectPolicies($this->object) as $phid => $policy) {
      $results[$phid] = $policy;
      unset($phids[$phid]);
    }

    foreach ($this->getObjectsFromWorkspace($phids) as $phid => $policy) {
      if (get_class($policy) != PhabricatorPolicy::class) {
        // We break the convention where the phid type in the key matches the
        // object in the value.
        continue;
      }
      $results[$phid] = $policy;
      unset($phids[$phid]);
    }

    // If we still need policies, we're going to have to fetch data. Bucket
    // the remaining policies into rule-based policies and handle-based
    // policies.
    if ($phids) {
      $named_policies = array();
      $rule_policies = array();
      $handle_policies = array();
      foreach ($phids as $phid) {
        $phid_type = phid_get_type($phid);
        switch ($phid_type) {
          case PhorgePolicyPHIDTypeNamedPolicy::TYPECONST:
            $named_policies[$phid] = $phid;
            break;
          case PhabricatorPolicyPHIDTypePolicy::TYPECONST:
            $rule_policies[$phid] = $phid;
            break;
          default:
            $handle_policies[$phid] = $phid;
            break;
        }
      }

      if ($named_policies) {
        // The user might not be allowed to see the Named Policy object, but
        // allowed to see the object it applies to.
        // We're loading everything, and we'll filter and hide them later.
        $named_policy_query = id(new PhorgeNamedPolicyQuery())
          ->setViewer(PhabricatorUser::getOmnipotentUser())
          ->withPHIDs(array_keys($named_policies))
          ->withCanApplyToObject($this->object);

        $loaded_named_policies = $named_policy_query->execute();
        $loaded_named_policies = mpull($loaded_named_policies, null, 'getPHID');

        list($_, $_, $named_rule, $named_handle) =
          $this->splitNamedPoliciesByEffectiveType($loaded_named_policies);

        // We already have all Global and Object rules in the cache.
        $rule_policies += array_fuse($named_rule);
        $handle_policies += array_fuse($named_handle);
      }


      if ($handle_policies) {
        if ($this->needPolicyDetails) {
          // We want these to display them later. Load anything the use can view
          $handles = id(new PhabricatorHandleQuery())
            ->setViewer($this->getViewer())
            ->setParentQuery($this)
            ->withPHIDs($handle_policies)
            ->execute();
        } else {
          // For policy filtering, we only need the PHID - don't load anything
          foreach ($handle_policies as $phid) {
            $handles[$phid] = id(new PhabricatorObjectHandle())
              ->setPHID($phid)
              ->setType(phid_get_type($phid))
              ->setPolicyFiltered(true);
          }
        }

        foreach ($handle_policies as $phid) {
          $results[$phid] = PhabricatorPolicy::newFromPolicyAndHandle(
            $phid,
            $handles[$phid]);
        }
      }

      if ($rule_policies) {
        $rules = id(new PhabricatorPolicy())->loadAllWhere(
          'phid IN (%Ls)',
          $rule_policies);
        $results += mpull($rules, null, 'getPHID');
      }

      if ($named_policies) {

        // as good a fallback as any?
        $noone = self::getGlobalPolicy(PhabricatorPolicies::POLICY_NOONE);
        $for_workspace = array();

        foreach ($loaded_named_policies as $named_phid => $named_policy) {
          $policy = idx($results, $named_policy->getEffectivePolicy(), $noone);

          $cloned_policy = id(clone($policy))
            ->makeEphemeral()
            ->setPHID($named_phid)
            ->setType(PhabricatorPolicyType::TYPE_MASKED)
            ->setShortName(null)
            ->setName(null)
            ->setHref(null);

          $results[$named_phid] = $cloned_policy;
          $for_workspace[$named_phid] = $cloned_policy;
        }

        // We're about the make a sub-query, so make sure we have copies
        // of everything we've loaded so far, to avoid cycles.
        // But don't add everything here, because we accept project phids and
        // user phids and return PhabricatorPolicy instead.
        // The objects we're adding here are Masked, so users can always see
        // them. We'll enrich the same objects later, if they pass the filter
        // for this viewer.
        $this->putObjectsInWorkspace($for_workspace);

        $visible_named_policies = id(new PhabricatorPolicyFilter())
          ->setViewer($this->getViewer())
          ->setParentQuery($this)
          ->requireCapabilities(array(PhabricatorPolicyCapability::CAN_VIEW))
          ->apply($loaded_named_policies);

        foreach ($visible_named_policies as $named_phid => $named_policy) {
          $results[$named_phid]
              ->setType(PhabricatorPolicyType::TYPE_NAMED)
              ->setHref($named_policy->getHref())
              ->setName($named_policy->getName())
              ->setIcon($named_policy->getIcon());
        }
      }

    }

    $results = array_select_keys($results, $requested);
    $results = msort($results, 'getSortKey');
    return $results;
  }

  public static function isGlobalPolicy($policy) {
    $global_policies = self::getGlobalPolicies();

    if (isset($global_policies[$policy])) {
      return true;
    }

    return false;
  }

  public static function getGlobalPolicy($policy) {
    if (!self::isGlobalPolicy($policy)) {
      throw new Exception(pht("Policy '%s' is not a global policy!", $policy));
    }
    return idx(self::getGlobalPolicies(), $policy);
  }

  private static function getGlobalPolicies() {
    static $constants = array(
      PhabricatorPolicies::POLICY_PUBLIC,
      PhabricatorPolicies::POLICY_USER,
      PhabricatorPolicies::POLICY_ADMIN,
      PhabricatorPolicies::POLICY_NOONE,
    );

    $results = array();
    foreach ($constants as $constant) {
      $results[$constant] = id(new PhabricatorPolicy())
        ->setType(PhabricatorPolicyType::TYPE_GLOBAL)
        ->setPHID($constant)
        ->setName(self::getGlobalPolicyName($constant))
        ->setShortName(self::getGlobalPolicyShortName($constant))
        ->setRules(array(
          array(
            'action' => 'allow',
            'rule' => PhorgeGlobalPolicyRule::class,
            'value' => $constant,
          ),
        ))
        ->makeEphemeral();
    }

    return $results;
  }

  private static function getGlobalPolicyName($policy) {
    switch ($policy) {
      case PhabricatorPolicies::POLICY_PUBLIC:
        return pht('Public (No Login Required)');
      case PhabricatorPolicies::POLICY_USER:
        return pht('All Users');
      case PhabricatorPolicies::POLICY_ADMIN:
        return pht('Administrators');
      case PhabricatorPolicies::POLICY_NOONE:
        return pht('No One');
      default:
        return pht('Unknown Policy');
    }
  }

  private static function getGlobalPolicyShortName($policy) {
    switch ($policy) {
      case PhabricatorPolicies::POLICY_PUBLIC:
        return pht('Public');
      default:
        return null;
    }
  }

  private function loadObjectPolicyPHIDs() {
    $phids = array();
    $viewer = $this->getViewer();

    if ($viewer->getPHID()) {
      $phids += $this->loadProjectPoliciesForViewer($viewer);
      $phids += $this->loadNamedPoliciesForViewer($viewer);

      // Include the "current viewer" policy. This improves consistency, but
      // is also useful for creating private instances of normally-shared object
      // types, like repositories.
      $phids[] = $viewer->getPHID();
    }

    $capabilities = $this->object->getCapabilities();
    foreach ($capabilities as $capability) {
      $policy = $this->object->getPolicy($capability);
      if (!$policy) {
        continue;
      }
      $phids[] = $policy;
    }

    // If this install doesn't have "Public" enabled, don't include it as an
    // option unless the object already has a "Public" policy. In this case we
    // retain the policy but enforce it as though it was "All Users".
    $show_public = PhabricatorEnv::getEnvConfig('policy.allow-public');
    foreach (self::getGlobalPolicies() as $phid => $policy) {
      if ($phid == PhabricatorPolicies::POLICY_PUBLIC) {
        if (!$show_public) {
          continue;
        }
      }
      $phids[] = $phid;
    }

    foreach (self::getObjectPolicies($this->object) as $phid => $policy) {
      $phids[] = $phid;
    }

    return $phids;
  }

  private function splitNamedPoliciesByEffectiveType($named_policies) {

    // these map named_policy_phid to the effective_policy_identifier,
    // split by the type of the effective policy.
    $global = array();
    $object = array();
    $custom = array();
    $handle = array();

    foreach ($named_policies as $phid => $named) {
      $effective = $named->getEffectivePolicy();
      if (self::isGlobalPolicy($effective)) {
        $global[$phid] = $effective;
        continue;
      }
      if (self::isObjectPolicy($effective)) {
        $object[$phid] = $effective;
        continue;
      }
      $phid_type = phid_get_type($effective);
      switch ($phid_type) {
        case PhorgePolicyPHIDTypeNamedPolicy::TYPECONST:
          phlog(
            pht(
              'Named Policy has invalid effective policy: %s -> %s',
              $phid,
              $effective));
          break;
        case PhabricatorPolicyPHIDTypePolicy::TYPECONST:
          $custom[$phid] = $effective;
          break;
        default:
          $handle[$phid] = $effective;
          break;
      }

    }

    return array($global, $object, $custom, $handle);
  }

  private function loadProjectPoliciesForViewer(PhabricatorUser $viewer) {
    $pref_key = PhabricatorPolicyFavoritesSetting::SETTINGKEY;

    $favorite_limit = 10;
    $default_limit = 5;

    // If possible, show the user's 10 most recently used projects.
    $favorites = $viewer->getUserSetting($pref_key);
    if (!is_array($favorites)) {
      $favorites = array();
    }
    $favorite_phids = array_keys($favorites);
    $favorite_phids = array_slice($favorite_phids, -$favorite_limit);

    if ($favorite_phids) {
      $projects = id(new PhabricatorProjectQuery())
        ->setViewer($viewer)
        ->setParentQuery($this)
        ->withPHIDs($favorite_phids)
        ->withIsMilestone(false)
        ->setLimit($favorite_limit)
        ->execute();
      $projects = mpull($projects, null, 'getPHID');
    } else {
      $projects = array();
    }

    // If we didn't find enough favorites, add some default projects. These
    // are just arbitrary projects that the viewer is a member of, but may
    // be useful on smaller installs and for new users until they can use
    // the control enough time to establish useful favorites.
    if (count($projects) < $default_limit) {
      $default_projects = id(new PhabricatorProjectQuery())
        ->setViewer($viewer)
        ->setParentQuery($this)
        ->withMemberPHIDs(array($viewer->getPHID()))
        ->withIsMilestone(false)
        ->withStatuses(
          array(
            PhabricatorProjectStatus::STATUS_ACTIVE,
          ))
        ->setLimit($default_limit)
        ->execute();
      $default_projects = mpull($default_projects, null, 'getPHID');
      $projects = $projects + $default_projects;
      $projects = array_slice($projects, 0, $default_limit);
    }

    return mpull($projects, 'getPHID', 'getPHID');
  }

  private function loadNamedPoliciesForViewer(PhabricatorUser $viewer) {
    // TODO add Named policies to Favorites

    $policies = id(new PhorgeNamedPolicyQuery())
      ->setViewer($viewer)
      ->setParentQuery($this)
      ->setLimit(4)
      ->execute();

    return mpull($policies, 'getPHID', 'getPHID');
  }

  protected function shouldDisablePolicyFiltering() {
    // Policy filtering of policies is currently perilous and not required by
    // the application.
    return true;
  }

  public function getQueryApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  public static function isSpecialPolicy($identifier) {
    if ($identifier === null) {
      return true;
    }

    if (self::isObjectPolicy($identifier)) {
      return true;
    }

    if (self::isGlobalPolicy($identifier)) {
      return true;
    }

    return false;
  }


/* -(  Object Policies  )---------------------------------------------------- */


  public static function isObjectPolicy($identifier) {
    $prefix = self::OBJECT_POLICY_PREFIX;
    return !strncmp($identifier, $prefix, strlen($prefix));
  }

  public static function getObjectPolicy($identifier) {
    if (!self::isObjectPolicy($identifier)) {
      return null;
    }

    $policies = self::getObjectPolicies(null);
    return idx($policies, $identifier);
  }

  public static function getObjectPolicyRule($identifier) {
    if (!self::isObjectPolicy($identifier)) {
      return null;
    }

    $rules = self::getObjectPolicyRules(null);
    return idx($rules, $identifier);
  }

  public static function getObjectPolicies($object) {
    $rule_map = self::getObjectPolicyRules($object);

    $results = array();
    foreach ($rule_map as $key => $rule) {
      $results[$key] = id(new PhabricatorPolicy())
        ->setType(PhabricatorPolicyType::TYPE_OBJECT)
        ->setPHID($key)
        ->setIcon($rule->getObjectPolicyIcon())
        ->setName($rule->getObjectPolicyName())
        ->setShortName($rule->getObjectPolicyShortName())
        ->setRules(array(
          array(
            'action' => 'allow',
            'rule' => get_class($rule),
            'value' => null,
          ),
        ))
        ->makeEphemeral();
    }

    return $results;
  }

  public static function getObjectPolicyRules($object) {
    $rules = id(new PhutilClassMapQuery())
      ->setAncestorClass(PhabricatorPolicyRule::class)
      ->execute();

    $results = array();
    foreach ($rules as $rule) {
      $key = $rule->getObjectPolicyKey();
      if (!$key) {
        continue;
      }

      $full_key = $rule->getObjectPolicyFullKey();
      if (isset($results[$full_key])) {
        throw new Exception(
          pht(
            'Two policy rules (of classes "%s" and "%s") define the same '.
            'object policy key ("%s"), but each object policy rule must use '.
            'a unique key.',
            get_class($rule),
            get_class($results[$full_key]),
            $key));
      }

      $results[$full_key] = $rule;
    }

    if ($object !== null) {
      foreach ($results as $key => $rule) {
        if (!$rule->canApplyToObject($object)) {
          unset($results[$key]);
        }
      }
    }

    return $results;
  }

  public static function getDefaultPolicyForObject(
    PhabricatorUser $viewer,
    PhabricatorPolicyInterface $object,
    $capability) {

    $phid = $object->getPHID();
    if (!$phid) {
      return null;
    }

    $type = phid_get_type($phid);

    $map = self::getDefaultObjectTypePolicyMap();

    if (empty($map[$type][$capability])) {
      return null;
    }

    $policy_phid = $map[$type][$capability];

    return id(new self())
      ->setViewer($viewer)
      ->withPHIDs(array($policy_phid))
      ->executeOne();
  }

  private static function getDefaultObjectTypePolicyMap() {
    static $map;

    if ($map === null) {
      $map = array();

      $apps = PhabricatorApplication::getAllApplications();
      foreach ($apps as $app) {
        $map += $app->getDefaultObjectTypePolicyMap();
      }
    }

    return $map;
  }


}
