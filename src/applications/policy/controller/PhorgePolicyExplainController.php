<?php

final class PhorgePolicyExplainController
  extends PhorgePolicyController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $phid = $request->getURIData('phid');
    $capability = $request->getURIData('capability');

    $object = id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();
    if (!$object) {
      return new Aphront404Response();
    }

    $policies = PhorgePolicyQuery::loadPolicies(
      $viewer,
      $object);

    $policy = idx($policies, $capability);
    if (!$policy) {
      return new Aphront404Response();
    }

    $handle = id(new PhorgeHandleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->executeOne();

    $object_name = $handle->getName();
    $object_uri = nonempty($handle->getURI(), '/');

    $dialog = id(new AphrontDialogView())
      ->setUser($viewer)
      ->setClass('aphront-access-dialog aphront-policy-explain-dialog')
      ->setTitle(pht('Policy Details: %s', $object_name))
      ->addCancelButton($object_uri, pht('Done'));

    $space_section = $this->buildSpaceSection(
      $object,
      $policy,
      $capability);

    $extended_section = $this->buildExtendedSection(
      $object,
      $capability);

    $exceptions_section = $this->buildExceptionsSection(
      $object,
      $capability);

    $object_section = $this->buildObjectSection(
      $object,
      $policy,
      $capability,
      $handle);

    $dialog->appendChild(
      array(
        $space_section,
        $extended_section,
        $exceptions_section,
        $object_section,
      ));


    return $dialog;
  }

  private function buildSpaceSection(
    PhorgePolicyInterface $object,
    PhorgePolicy $policy,
    $capability) {
    $viewer = $this->getViewer();

    if (!($object instanceof PhorgeSpacesInterface)) {
      return null;
    }

    if (!PhorgeSpacesNamespaceQuery::getSpacesExist()) {
      return null;
    }

    $space_phid = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
      $object);

    $spaces = PhorgeSpacesNamespaceQuery::getViewerSpaces($viewer);
    $space = idx($spaces, $space_phid);
    if (!$space) {
      return null;
    }

    $space_policies = PhorgePolicyQuery::loadPolicies($viewer, $space);
    $space_policy = idx($space_policies, PhorgePolicyCapability::CAN_VIEW);
    if (!$space_policy) {
      return null;
    }

    $doc_href = PhorgeEnv::getDoclink('Spaces User Guide');
    $capability_name = $this->getCapabilityName($capability);

    $space_section = id(new PHUIPolicySectionView())
      ->setViewer($viewer)
      ->setIcon('fa-th-large bluegrey')
      ->setHeader(pht('Space'))
      ->setDocumentationLink(pht('Spaces Documentation'), $doc_href)
      ->appendList(
        array(
          array(
            phutil_tag('strong', array(), pht('Space:')),
            ' ',
            $viewer->renderHandle($space_phid)->setAsTag(true),
          ),
          array(
            phutil_tag('strong', array(), pht('%s:', $capability_name)),
            ' ',
            $space_policy->getShortName(),
          ),
        ))
      ->appendParagraph(
        pht(
          'This object is in %s and can only be seen or edited by users '.
          'with access to view objects in the space.',
          $viewer->renderHandle($space_phid)));

    $space_explanation = PhorgePolicy::getPolicyExplanation(
      $viewer,
      $space_policy->getPHID());
    $items = array();
    $items[] = $space_explanation;

    $space_section
      ->appendParagraph(pht('Users who can see objects in this space:'))
      ->appendList($items);

    $view_capability = PhorgePolicyCapability::CAN_VIEW;
    if ($capability == $view_capability) {
      $stronger = $space_policy->isStrongerThan($policy);
      if ($stronger) {
        $space_section->appendHint(
          pht(
            'The space this object is in has a more restrictive view '.
            'policy ("%s") than the object does ("%s"), so the space\'s '.
            'view policy is shown as a hint instead of the object policy.',
            $space_policy->getShortName(),
            $policy->getShortName()));
      }
    }

    $space_section->appendHint(
      pht(
        'After a user passes space policy checks, they must still pass '.
        'object policy checks.'));

    return $space_section;
  }

  private function getCapabilityName($capability) {
    $capability_name = $capability;
    $capobj = PhorgePolicyCapability::getCapabilityByKey($capability);
    if ($capobj) {
      $capability_name = $capobj->getCapabilityName();
    }

    return $capability_name;
  }

  private function buildExtendedSection(
    PhorgePolicyInterface $object,
    $capability) {
    $viewer = $this->getViewer();

    if (!($object instanceof PhorgeExtendedPolicyInterface)) {
      return null;
    }

    $extended_rules = $object->getExtendedPolicy($capability, $viewer);
    if (!$extended_rules) {
      return null;
    }

    $items = array();
    foreach ($extended_rules as $extended_rule) {
      $extended_target = $extended_rule[0];
      $extended_capabilities = (array)$extended_rule[1];
      if (is_object($extended_target)) {
        $extended_target = $extended_target->getPHID();
      }

      foreach ($extended_capabilities as $extended_capability) {
        $ex_name = $this->getCapabilityName($extended_capability);
        $items[] = array(
          phutil_tag('strong', array(), pht('%s:', $ex_name)),
          ' ',
          $viewer->renderHandle($extended_target)->setAsTag(true),
        );
      }
    }

    return id(new PHUIPolicySectionView())
      ->setViewer($viewer)
      ->setIcon('fa-link')
      ->setHeader(pht('Required Capabilities on Other Objects'))
      ->appendParagraph(
        pht(
          'To access this object, users must have first have access '.
          'capabilities on these other objects:'))
      ->appendList($items);
  }

  private function buildExceptionsSection(
    PhorgePolicyInterface $object,
    $capability) {
    $viewer = $this->getViewer();

    $exceptions = PhorgePolicy::getSpecialRules(
      $object,
      $viewer,
      $capability,
      false);

    if (!$exceptions) {
      return null;
    }

    return id(new PHUIPolicySectionView())
      ->setViewer($viewer)
      ->setIcon('fa-unlock-alt red')
      ->setHeader(pht('Special Rules'))
      ->appendParagraph(
        pht(
          'This object has special rules which override normal object '.
          'policy rules:'))
      ->appendList($exceptions);
  }

  private function buildObjectSection(
    PhorgePolicyInterface $object,
    PhorgePolicy $policy,
    $capability,
    PhorgeObjectHandle $handle) {

    $viewer = $this->getViewer();
    $capability_name = $this->getCapabilityName($capability);

    $object_section = id(new PHUIPolicySectionView())
      ->setViewer($viewer)
      ->setIcon($handle->getIcon().' bluegrey')
      ->setHeader(pht('Object Policy'))
      ->appendParagraph(
        array(
          array(
            phutil_tag('strong', array(), pht('%s:', $capability_name)),
            ' ',
            $policy->getShortName(),
          ),
        ))
      ->appendParagraph(
        pht(
          'In detail, this means that these users can take this action, '.
          'provided they pass all of the checks described above first:'))
      ->appendList(
        array(
          PhorgePolicy::getPolicyExplanation(
            $viewer,
            $policy->getPHID()),
        ));

    if ($policy->isCustomPolicy()) {
      $rules_view = id(new PhorgePolicyRulesView())
        ->setViewer($viewer)
        ->setPolicy($policy);
      $object_section->appendRulesView($rules_view);
    }

    return $object_section;
  }

}
