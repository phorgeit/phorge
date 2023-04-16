<?php

final class PhorgePolicy
  extends PhorgePolicyDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  const ACTION_ALLOW = 'allow';
  const ACTION_DENY = 'deny';

  private $name;
  private $shortName;
  private $type;
  private $href;
  private $workflow;
  private $icon;

  protected $rules = array();
  protected $defaultAction = self::ACTION_DENY;

  private $ruleObjects = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'rules' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'defaultAction' => 'text32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePolicyPHIDTypePolicy::TYPECONST);
  }

  public static function newFromPolicyAndHandle(
    $policy_identifier,
    PhorgeObjectHandle $handle = null) {

    $is_global = PhorgePolicyQuery::isGlobalPolicy($policy_identifier);
    if ($is_global) {
      return PhorgePolicyQuery::getGlobalPolicy($policy_identifier);
    }

    $policy = PhorgePolicyQuery::getObjectPolicy($policy_identifier);
    if ($policy) {
      return $policy;
    }

    if (!$handle) {
      throw new Exception(
        pht(
          "Policy identifier is an object PHID ('%s'), but no object handle ".
          "was provided. A handle must be provided for object policies.",
          $policy_identifier));
    }

    $handle_phid = $handle->getPHID();
    if ($policy_identifier != $handle_phid) {
      throw new Exception(
        pht(
          "Policy identifier is an object PHID ('%s'), but the provided ".
          "handle has a different PHID ('%s'). The handle must correspond ".
          "to the policy identifier.",
          $policy_identifier,
          $handle_phid));
    }

    $policy = id(new PhorgePolicy())
      ->setPHID($policy_identifier)
      ->setHref($handle->getURI());

    $phid_type = phid_get_type($policy_identifier);
    switch ($phid_type) {
      case PhorgeProjectProjectPHIDType::TYPECONST:
        $policy
          ->setType(PhorgePolicyType::TYPE_PROJECT)
          ->setName($handle->getName())
          ->setIcon($handle->getIcon());
        break;
      case PhorgePeopleUserPHIDType::TYPECONST:
        $policy->setType(PhorgePolicyType::TYPE_USER);
        $policy->setName($handle->getFullName());
        break;
      case PhorgePolicyPHIDTypePolicy::TYPECONST:
        // TODO: This creates a weird handle-based version of a rule policy.
        // It behaves correctly, but can't be applied since it doesn't have
        // any rules. It is used to render transactions, and might need some
        // cleanup.
        break;
      default:
        $policy->setType(PhorgePolicyType::TYPE_MASKED);
        $policy->setName($handle->getFullName());
        break;
    }

    $policy->makeEphemeral();

    return $policy;
  }

  public function setType($type) {
    $this->type = $type;
    return $this;
  }

  public function getType() {
    if (!$this->type) {
      return PhorgePolicyType::TYPE_CUSTOM;
    }
    return $this->type;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getName() {
    if (!$this->name) {
      return pht('Custom Policy');
    }
    return $this->name;
  }

  public function setShortName($short_name) {
    $this->shortName = $short_name;
    return $this;
  }

  public function getShortName() {
    if ($this->shortName) {
      return $this->shortName;
    }
    return $this->getName();
  }

  public function setHref($href) {
    $this->href = $href;
    return $this;
  }

  public function getHref() {
    return $this->href;
  }

  public function setWorkflow($workflow) {
    $this->workflow = $workflow;
    return $this;
  }

  public function getWorkflow() {
    return $this->workflow;
  }

  public function setIcon($icon) {
    $this->icon = $icon;
    return $this;
  }

  public function getIcon() {
    if ($this->icon) {
      return $this->icon;
    }

    switch ($this->getType()) {
      case PhorgePolicyType::TYPE_GLOBAL:
        static $map = array(
          PhorgePolicies::POLICY_PUBLIC  => 'fa-globe',
          PhorgePolicies::POLICY_USER    => 'fa-users',
          PhorgePolicies::POLICY_ADMIN   => 'fa-eye',
          PhorgePolicies::POLICY_NOONE   => 'fa-ban',
        );
        return idx($map, $this->getPHID(), 'fa-question-circle');
      case PhorgePolicyType::TYPE_USER:
        return 'fa-user';
      case PhorgePolicyType::TYPE_PROJECT:
        return 'fa-briefcase';
      case PhorgePolicyType::TYPE_CUSTOM:
      case PhorgePolicyType::TYPE_MASKED:
        return 'fa-certificate';
      default:
        return 'fa-question-circle';
    }
  }

  public function getSortKey() {
    return sprintf(
      '%02d%s',
      PhorgePolicyType::getPolicyTypeOrder($this->getType()),
      $this->getSortName());
  }

  private function getSortName() {
    if ($this->getType() == PhorgePolicyType::TYPE_GLOBAL) {
      static $map = array(
        PhorgePolicies::POLICY_PUBLIC  => 0,
        PhorgePolicies::POLICY_USER    => 1,
        PhorgePolicies::POLICY_ADMIN   => 2,
        PhorgePolicies::POLICY_NOONE   => 3,
      );
      return idx($map, $this->getPHID());
    }
    return $this->getName();
  }

  public static function getPolicyExplanation(
    PhorgeUser $viewer,
    $policy) {

    $type = phid_get_type($policy);
    if ($type === PhorgeProjectProjectPHIDType::TYPECONST) {
      $handle = id(new PhorgeHandleQuery())
        ->setViewer($viewer)
        ->withPHIDs(array($policy))
        ->executeOne();

      return pht(
        'Members of the project "%s" can take this action.',
        $handle->getFullName());
    }

    return self::getOpaquePolicyExplanation($viewer, $policy);
  }

  public static function getOpaquePolicyExplanation(
    PhorgeUser $viewer,
    $policy) {

    $rule = PhorgePolicyQuery::getObjectPolicyRule($policy);
    if ($rule) {
      return $rule->getPolicyExplanation();
    }

    switch ($policy) {
      case PhorgePolicies::POLICY_PUBLIC:
        return pht(
          'This object is public and can be viewed by anyone, even if they '.
          'do not have an account on this server.');
      case PhorgePolicies::POLICY_USER:
        return pht('Logged in users can take this action.');
      case PhorgePolicies::POLICY_ADMIN:
        return pht('Administrators can take this action.');
      case PhorgePolicies::POLICY_NOONE:
        return pht('By default, no one can take this action.');
      default:
        $handle = id(new PhorgeHandleQuery())
          ->setViewer($viewer)
          ->withPHIDs(array($policy))
          ->executeOne();

        $type = phid_get_type($policy);
        if ($type == PhorgeProjectProjectPHIDType::TYPECONST) {
          return pht(
            'Members of a particular project can take this action. (You '.
            'can not see this object, so the name of this project is '.
            'restricted.)');
        } else if ($type == PhorgePeopleUserPHIDType::TYPECONST) {
          return pht(
            '%s can take this action.',
            $handle->getFullName());
        } else if ($type == PhorgePolicyPHIDTypePolicy::TYPECONST) {
          return pht(
            'This object has a custom policy controlling who can take this '.
            'action.');
        } else {
          return pht(
            'This object has an unknown or invalid policy setting ("%s").',
            $policy);
        }
    }
  }

  public function getFullName() {
    switch ($this->getType()) {
      case PhorgePolicyType::TYPE_PROJECT:
        return pht('Members of Project: %s', $this->getName());
      case PhorgePolicyType::TYPE_MASKED:
        return pht('Other: %s', $this->getName());
      case PhorgePolicyType::TYPE_USER:
        return pht('Only User: %s', $this->getName());
      default:
        return $this->getName();
    }
  }

  public function newRef(PhorgeUser $viewer) {
    return id(new PhorgePolicyRef())
      ->setViewer($viewer)
      ->setPolicy($this);
  }

  public function isProjectPolicy() {
    return ($this->getType() === PhorgePolicyType::TYPE_PROJECT);
  }

  public function isCustomPolicy() {
    return ($this->getType() === PhorgePolicyType::TYPE_CUSTOM);
  }

  public function isMaskedPolicy() {
    return ($this->getType() === PhorgePolicyType::TYPE_MASKED);
  }

  /**
   * Return a list of custom rule classes (concrete subclasses of
   * @{class:PhorgePolicyRule}) this policy uses.
   *
   * @return list<string> List of class names.
   */
  public function getCustomRuleClasses() {
    $classes = array();

    foreach ($this->getRules() as $rule) {
      if (!is_array($rule)) {
        // This rule is invalid. We'll reject it later, but don't need to
        // extract anything from it for now.
        continue;
      }

      $class = idx($rule, 'rule');
      try {
        if (class_exists($class)) {
          $classes[$class] = $class;
        }
      } catch (Exception $ex) {
        continue;
      }
    }

    return array_keys($classes);
  }

  /**
   * Return a list of all values used by a given rule class to implement this
   * policy. This is used to bulk load data (like project memberships) in order
   * to apply policy filters efficiently.
   *
   * @param string Policy rule classname.
   * @return list<wild> List of values used in this policy.
   */
  public function getCustomRuleValues($rule_class) {
    $values = array();
    foreach ($this->getRules() as $rule) {
      if ($rule['rule'] == $rule_class) {
        $values[] = $rule['value'];
      }
    }
    return $values;
  }

  public function attachRuleObjects(array $objects) {
    $this->ruleObjects = $objects;
    return $this;
  }

  public function getRuleObjects() {
    return $this->assertAttached($this->ruleObjects);
  }


  /**
   * Return `true` if this policy is stronger (more restrictive) than some
   * other policy.
   *
   * Because policies are complicated, determining which policies are
   * "stronger" is not trivial. This method uses a very coarse working
   * definition of policy strength which is cheap to compute, unambiguous,
   * and intuitive in the common cases.
   *
   * This method returns `true` if the //class// of this policy is stronger
   * than the other policy, even if the policies are (or might be) the same in
   * practice. For example, "Members of Project X" is considered a stronger
   * policy than "All Users", even though "Project X" might (in some rare
   * cases) contain every user.
   *
   * Generally, the ordering here is:
   *
   *   - Public
   *   - All Users
   *   - (Everything Else)
   *   - No One
   *
   * In the "everything else" bucket, we can't make any broad claims about
   * which policy is stronger (and we especially can't make those claims
   * cheaply).
   *
   * Even if we fully evaluated each policy, the two policies might be
   * "Members of X" and "Members of Y", each of which permits access to some
   * set of unique users. In this case, neither is strictly stronger than
   * the other.
   *
   * @param PhorgePolicy Other policy.
   * @return bool `true` if this policy is more restrictive than the other
   *  policy.
   */
  public function isStrongerThan(PhorgePolicy $other) {
    $this_policy = $this->getPHID();
    $other_policy = $other->getPHID();

    $strengths = array(
      PhorgePolicies::POLICY_PUBLIC => -2,
      PhorgePolicies::POLICY_USER => -1,
      // (Default policies have strength 0.)
      PhorgePolicies::POLICY_NOONE => 1,
    );

    $this_strength = idx($strengths, $this_policy, 0);
    $other_strength = idx($strengths, $other_policy, 0);

    return ($this_strength > $other_strength);
  }

  public function isStrongerThanOrEqualTo(PhorgePolicy $other) {
    $this_policy = $this->getPHID();
    $other_policy = $other->getPHID();

    if ($this_policy === $other_policy) {
      return true;
    }

    return $this->isStrongerThan($other);
  }

  public function isValidPolicyForEdit() {
    return $this->getType() !== PhorgePolicyType::TYPE_MASKED;
  }

  public static function getSpecialRules(
    PhorgePolicyInterface $object,
    PhorgeUser $viewer,
    $capability,
    $active_only) {

    $exceptions = array();
    if ($object instanceof PhorgePolicyCodexInterface) {
      $codex = id(PhorgePolicyCodex::newFromObject($object, $viewer))
        ->setCapability($capability);
      $rules = $codex->getPolicySpecialRuleDescriptions();

      foreach ($rules as $rule) {
        $is_active = $rule->getIsActive();
        if ($is_active) {
          $rule_capabilities = $rule->getCapabilities();
          if ($rule_capabilities) {
            if (!in_array($capability, $rule_capabilities)) {
              $is_active = false;
            }
          }
        }

        if (!$is_active && $active_only) {
          continue;
        }

        $description = $rule->getDescription();

        if (!$is_active) {
          $description = phutil_tag(
            'span',
            array(
              'class' => 'phui-policy-section-view-inactive-rule',
            ),
            $description);
        }

        $exceptions[] = $description;
      }
    }

    if (!$exceptions) {
      if (method_exists($object, 'describeAutomaticCapability')) {
        $exceptions = (array)$object->describeAutomaticCapability($capability);
        $exceptions = array_filter($exceptions);
      }
    }

    return $exceptions;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    // NOTE: We implement policies only so we can comply with the interface.
    // The actual query skips them, as enforcing policies on policies seems
    // perilous and isn't currently required by the application.
    return PhorgePolicies::POLICY_PUBLIC;
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->delete();
  }


}
